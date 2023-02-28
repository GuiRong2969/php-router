<?php declare(strict_types=1);

namespace Guirong\RouteTest;

use Guirong\Route\Route;
use Guirong\Route\Router;
use Guirong\RouteTest\Controllers\DemoController;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Version;
use Throwable;
use function in_array;
use function Guirong\Route\createRouter;
use function sprintf;

class RouterTest extends TestCase
{
    public function testConfig(): void
    {
        $router = Router::create();
        $router->setName('my-router');

        $this->assertSame('my-router', $router->getName());

        $router->addGlobalParams([
            'myArg' => '\w{5}'
        ]);
        $this->assertArrayHasKey('myArg', $router->getGlobalParams());

        $router->setGlobalOptions(['opt1' => 'val1']);
        $this->assertArrayHasKey('opt1', $router->getGlobalOptions());
    }

    public function testAddRoutes(): void
    {
        $r = new Router([]);

        $r->get('/', 'handler0');
        $r->get('/hi/{name}', 'handler3', [
            'name' => '\w+',
        ]);

        $r1 = $r->get('/my[/{name}[/{age}]]', 'handler2', [
            'age' => '\d+'
        ]);

        $this->assertTrue($r->count() > 1);
        $this->assertNotEmpty($r->getRoutes());

        $isGt8 = (int)Version::series() > 7;
        if ($isGt8) {
            $this->assertTrue(in_array('name', $r1->getPathVars(), true));
            $this->assertTrue(in_array('age', $r1->getPathVars(), true));
            $this->assertStringContainsString('GET     /my[/{name}[/{age}]]', (string)$r1);
        } else {
            $this->assertContains('name', $r1->getPathVars());
            $this->assertContains('age', $r1->getPathVars());
            $this->assertContains('GET     /my[/{name}[/{age}]]', (string)$r1);
        }

        $this->assertArrayHasKey('age', $r1->getBindVars());

        foreach (Router::METHODS_ARRAY as $method) {
            $r->$method("/$method", "handle_$method");
        }
        $string = (string)$r;
        foreach (Router::METHODS_ARRAY as $method) {
            $s = sprintf('%-7s %-25s --> %s', $method, "/$method", "handle_$method");
            if ($isGt8) {
                $this->assertStringContainsString($s, $string);
            } else {
                $this->assertContains($s, $string);
            }
        }

        $r->add('ANY', '/any', 'handler_any');
        $string = $r->toString();
        foreach (Router::METHODS_ARRAY as $method) {
            $s = sprintf('%-7s %-25s --> %s', $method, '/any', 'handler_any');
            if ($isGt8) {
                $this->assertStringContainsString($s, $string);
            } else {
                $this->assertContains($s, $string);
            }
        }

        $this->expectExceptionMessage('The method and route handler is not allow empty.');
        $r->add('GET', '', '');

        $this->expectException(InvalidArgumentException::class);
        $r->add('invalid', '/path', '/handler');

        try {
            $r->add('invalid', '/path', '/handler');
        } catch (Throwable $e) {
            if ($isGt8) {
                $this->assertStringContainsString('The method [INVALID] is not supported', $e->getMessage());
            } else {
                $this->assertContains('The method [INVALID] is not supported', $e->getMessage());
            }
        }
    }

    public function testAddRoute(): void
    {
        $router = createRouter(function () {
            //
        });

        $r1 = Route::create('GET', '/path1', 'handler0');
        $r1->setName('r1');
        $router->addRoute($r1);

        $r2 = Route::create('GET', '/path2', 'handler2');
        $r2->namedTo('r2', $router, true);

        $r3 = $router->add('get', '/path3', 'handler3');
        $r3->namedTo('r3', $router);

        $r4 = $router->add('get', '/path4', 'handler4', [], ['name' => 'r4']);
        $r5 = Route::create('get', '/path5', 'handler5', [], ['name' => 'r5'])
                   ->attachTo($router);

        $this->assertEmpty($router->getRoute('not-exist'));
        $this->assertEquals($r1, $router->getRoute('r1'));
        $this->assertEquals($r2, $router->getRoute('r2'));
        $this->assertEquals($r4, $router->getRoute('r4'));
        $this->assertEquals($r5, $router->getRoute('r5'));

        $ret = $router->getRoute('r3');
        $this->assertEquals($r3, $ret);
        $this->assertEquals([
            'path'        => '/path3',
            'method'      => 'GET',
            'handlerName' => 'handler3',
        ], $ret->info());
    }

    public function testStaticRoute(): void
    {
        /** @var Router $router */
        $router = Router::create();
        $router->get('/', 'handler0');
        $router->get('/about', 'handler1');
        $router->post('/some/to/path', 'handler2');

        /** @var Route $route */
        [$status, $path, $route] = $router->match('/');
        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('/', $path);
        $this->assertSame('handler0', $route->getHandler());

        // match use HEAD
        [$status, ,] = $router->match('/', 'HEAD');
        $this->assertSame(Router::FOUND, $status);

        [$status, $path, $route] = $router->match('about');
        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('/about', $path);
        $this->assertSame('handler1', $route->getHandler());

        [$status, $path, $route] = $router->match('/some//to/path', 'post');
        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('/some/to/path', $path);
        $this->assertSame('handler2', $route->getHandler());

        [$status, $path,] = $router->match('not-exist');
        $this->assertSame(Router::NOT_FOUND, $status);
        $this->assertSame('/not-exist', $path);

        // add fallback route.
        $router->any('/*', 'fb_handler');
        [$status, $path,] = $router->match('not-exist');
        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('/not-exist', $path);
    }

    public function testOptionalParamRoute(): void
    {
        /** @var Router $router */
        $router = Router::create();
        $router->get('/about[.html]', 'handler0');
        $router->get('/test1[/optional]', 'handler1');

        /** @var Route $route */

        // route: '/about'
        [$status, , $route] = $router->match('/about');
        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('handler0', $route->getHandler());

        // route: '/about.html'
        [$status, , $route] = $router->match('/about.html');
        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('handler0', $route->getHandler());

        // route: '/test1'
        [$status, , $route] = $router->match('/test1');
        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('handler1', $route->getHandler());

        // route: '/test1/optional'
        [$status, , $route] = $router->match('/test1/optional');
        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('handler1', $route->getHandler());

        // route: '/test1/other'
        [$status, ,] = $router->match('/test1/other');
        $this->assertSame(Router::NOT_FOUND, $status);
    }

    public function testParamRoute(): void
    {
        $router = Router::create();
        /** @var Route $route */
        $route = $router->get('/hi/{name}', 'handler3', [
            'name' => '\w+',
        ]);

        $this->assertEquals('#^/hi/(\w+)$#', $route->getPathRegex());

        // int param
        [$status, $path, $route] = $router->match('/hi/3456');

        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('/hi/3456', $path);
        $this->assertSame('/hi/{name}', $route->getPath());
        $this->assertSame('handler3', $route->getHandler());
        $this->assertSame('3456', $route->getParam('name'));

        // string param
        [$status, $path, $route] = $router->match('/hi/tom');

        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('/hi/tom', $path);
        $this->assertSame('/hi/{name}', $route->getPath());
        $this->assertSame('handler3', $route->getHandler());
        $this->assertArrayHasKey('name', $route->getParams());
        $this->assertSame('tom', $route->getParam('name'));

        // invalid
        [$status, ,] = $router->match('/hi/dont-match');
        $this->assertSame(Router::NOT_FOUND, $status);
    }

    public function testComplexRoute(): void
    {
        $router = Router::create();
        // handleMethodNotAllowed
        $router->handleMethodNotAllowed = true;

        /** @var Route $route */
        $route = $router->get('/my[/{name}[/{age}]]', 'handler2', [
            'age' => '\d+'
        ])->setOptions([
            'defaults' => [
                'name' => 'God',
                'age'  => 25,
            ]
        ]);

        $this->assertSame('handler2', $route->getHandler());
        $this->assertContains('age', $route->getPathVars());
        $this->assertContains('name', $route->getPathVars());

        // access '/my'
        [$status, $path, $route] = $router->match('/my');

        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('/my', $path);
        $this->assertSame('handler2', $route->getHandler());
        $this->assertArrayHasKey('defaults', $route->getOptions());
        $this->assertArrayHasKey('age', $route->getParams());
        $this->assertArrayHasKey('name', $route->getParams());
        $this->assertSame('God', $route->getParam('name'));
        $this->assertSame(25, $route->getParam('age'));

        // access '/my/tom'
        [$status, $path, $route] = $router->match('/my/tom');

        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('/my/tom', $path);
        $this->assertSame('handler2', $route->getHandler());
        $this->assertSame('tom', $route->getParam('name'));
        $this->assertSame(25, $route->getParam('age'));

        // access '/my/tom/45'
        [$status, $path, $route] = $router->match('/my/tom/45');

        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('/my/tom/45', $path);
        $this->assertSame('handler2', $route->getHandler());
        $this->assertSame('tom', $route->getParam('name'));
        $this->assertSame(45, (int)$route->getParam('age'));

        // use HEAD
        $ret = $router->match('/my/tom/45', 'HEAD');
        $this->assertSame(Router::FOUND, $ret[0]);

        // not allowed
        $ret = $router->match('/my/tom/45', 'POST');
        $this->assertSame(Router::METHOD_NOT_ALLOWED, $ret[0]);
        $this->assertEquals(['GET'], $ret[2]);

        // not found
        $ret = $router->match('/my/tom/not-match');
        $this->assertSame(Router::NOT_FOUND, $ret[0]);
    }

    public function testMatchAutoRoute(): void
    {
        $router = Router::create([
            // enable autoRoute
            // you can access '/demo' '/admin/user/info', Don't need to configure any route
            'autoRoute'           => true,
            'namespaceUcFirst'    => true,
            'controllerNamespace' => 'Guirong\RouteTest\Controllers',
        ]);

        /** @var Route $route */

        [$status, $path, $route] = $router->match('///demo');
        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('/demo', $path);
        $this->assertSame(DemoController::class, $route->getHandler());

        [$status, $path, $route] = $router->match('/admin/user/info');
        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('/admin/user/info', $path);
        $this->assertSame('Guirong\RouteTest\Controllers\Admin\UserController@info', $route->getHandler());

        [$status, $path,] = $router->match('/not-exist');
        $this->assertSame(Router::NOT_FOUND, $status);
        $this->assertSame('/not-exist', $path);
    }

    public function testNotFound(): void
    {
        $router = Router::create();
        $router->get('/hi/{name}', 'handler3', [
            'name' => '\w+',
        ]);

        [$status, $path,] = $router->match('/not-exist');
        $this->assertSame(Router::NOT_FOUND, $status);
        $this->assertSame('/not-exist', $path);

        [$status, $path,] = $router->match('/hi');
        $this->assertSame(Router::NOT_FOUND, $status);
        $this->assertSame('/hi', $path);
    }

    public function testRequestMethods(): void
    {
        $router = Router::create([
            'handleMethodNotAllowed' => true,
        ]);
        $router->get('/hi/{name}', 'handler3', [
            'name' => '\w+',
        ]);
        $router->map(['POST', 'PUT'], '/hi/{name}', 'handler4');

        /** @var Route $route */

        // GET
        [$status, , $route] = $router->match('/hi/tom', 'get');
        $this->assertSame(Router::FOUND, $status);
        $this->assertArrayHasKey('name', $route->getParams());
        $this->assertSame('handler3', $route->getHandler());

        // POST
        [$status, , $route] = $router->match('/hi/tom', 'post');
        $this->assertSame(Router::FOUND, $status);
        $this->assertArrayHasKey('name', $route->getParams());
        $this->assertSame('handler4', $route->getHandler());
        $this->assertEquals('tom', $route->getParam('name'));

        // PUT
        [$status, , $route] = $router->match('/hi/john', 'put');
        $this->assertSame(Router::FOUND, $status);
        $this->assertSame('handler4', $route->getHandler());
        $this->assertArrayHasKey('name', $route->getParams());
        $this->assertEquals('john', $route->getParam('name'));

        // DELETE
        [$status, , $methods] = $router->match('/hi/tom', 'delete');
        $this->assertSame(Router::METHOD_NOT_ALLOWED, $status);
        $this->assertCount(3, $methods);
        $this->assertEquals(['GET', 'POST', 'PUT'], $methods);
    }

    public function testMiddleware(): void
    {
        $router = Router::create();
        $router->use('func0', 'func1');

        // global middleware
        $this->assertSame(['func0', 'func1'], $router->getChains());

        $router->group('/grp', function (Router $r) use (&$r1) {
            $r1 = $r->get('/path', 'h0')->push('func2');
        }, ['func3', 'func4'], ['n1' => 'v1']);

        /** @var Route $route */
        [$status, , $route] = $router->match('/grp/path', 'get');

        $this->assertSame(Router::FOUND, $status);
        $this->assertSame($r1, $route);
        $this->assertSame(['func3', 'func4', 'func2'], $route->getChains());
        $this->assertArrayHasKey('n1', $route->getOptions());
    }
}
