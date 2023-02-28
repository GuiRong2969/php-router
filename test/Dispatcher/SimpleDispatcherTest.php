<?php declare(strict_types=1);

namespace Guirong\RouteTest\Dispatcher;

use Guirong\PhpRouter\Dispatcher\SimpleDispatcher;
use Guirong\PhpRouter\Router;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class SimpleDispatcherTest
 *
 * @package Guirong\RouteTest\Dispatcher
 */
class SimpleDispatcherTest extends TestCase
{
    private static $buffer = '';

    public static function resetBuffer(): void
    {
        self::$buffer = '';
    }

    /**
     * @throws Throwable
     */
    public function testDispatchUri(): void
    {
        $router = new Router();
        $router->get('/', static function () {
            self::$buffer = 'hello';
        });

        $d = new SimpleDispatcher([], $router);

        $bakServer = $_SERVER;

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $d->dispatchUri();

        $this->assertSame('hello', self::$buffer);

        $_SERVER = $bakServer;
    }

    /**
     * @throws Throwable
     */
    public function testDispatchUri2(): void
    {
        $router = new Router();
        $router->get('/', static function () {
            self::$buffer = 'hello';
        });

        $d = SimpleDispatcher::create([], $router);

        $bakServer = $_SERVER;

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $d->dispatchUri();

        $this->assertSame('hello', self::$buffer);

        $_SERVER = $bakServer;
    }
}
