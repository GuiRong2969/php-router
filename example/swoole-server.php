<?php declare(strict_types=1);
/**
 * User: Rong Gui
 * you can test use:
 *  php example/swoole-server.php
 * then you can access url: http://127.0.0.1:5675
 */

use Guirong\PhpRouter\Dispatcher\Dispatcher;

require dirname(__DIR__) . '/test/boot.php';

$router = new \Guirong\PhpRouter\ServerRouter();

// set config
$router->config([
    'ignoreLastSlash' => true,

    'tmpCacheNumber' => 100,

    // enable autoRoute
    // you can access '/demo' '/admin/user/info', Don't need to configure any route
    'autoRoute' => 1,
    'controllerNamespace' => 'Guirong\RouteTest\Controllers',
]);

$router->get('/routes', function () use ($router) {
    return $router->toString();
});

/** @var array $routes */
$hasRouter = true;
$routes = require __DIR__ . '/some-routes.php';

$dispatcher = new Dispatcher([
    'dynamicAction' => true,
]);
$dispatcher->setRouter($router);

// on notFound, output a message.
$dispatcher->on(Dispatcher::ON_NOT_FOUND, function ($path) {
    echo "the page $path not found!";
});

$server = new \Swoole\Http\Server('127.0.0.1', '5675', SWOOLE_BASE);
$server->set([
    //
]);

$server->on('request', function ($request, $response) use ($dispatcher) {
    /** @var  \Swoole\Http\Response $response */
    $uri = $request->server['request_uri'];
    $method = $request->server['request_method'];

    // fwrite(STDOUT, "request $method $uri\n");

    ob_start();
    $ret = $dispatcher->dispatchUri($uri, $method);
    $content = ob_get_clean();

    if (!$ret) {
        $ret = $content;
    }

    $response->end($ret);
});

echo "http server listen on http://127.0.0.1:5675\n";
$server->start();
