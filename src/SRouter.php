<?php declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\PhpRouter;

use Closure;
use Guirong\PhpRouter\Dispatcher\DispatcherInterface;
use InvalidArgumentException;
use LogicException;
use function method_exists;

/**
 * Class SRoute - this is static class version
 * @package Guirong\PhpRouter
 * @method static get(string $path, mixed $handler, array $binds = [], array $opts = [])
 * @method static post(string $path, mixed $handler, array $binds = [], array $opts = [])
 * @method static put(string $path, mixed $handler, array $binds = [], array $opts = [])
 * @method static delete(string $path, mixed $handler, array $binds = [], array $opts = [])
 * @method static options(string $path, mixed $handler, array $binds = [], array $opts = [])
 * @method static head(string $path, mixed $handler, array $binds = [], array $opts = [])
 * @method static search(string $path, mixed $handler, array $binds = [], array $opts = [])
 * @method static connect(string $path, mixed $handler, array $binds = [], array $opts = [])
 * @method static trace(string $path, mixed $handler, array $binds = [], array $opts = [])
 * @method static any(string $path, mixed $handler, array $binds = [], array $opts = [])
 * @method static add(string $method, string $path, mixed $handler, array $binds = [], array $opts = [])
 * @method static map(array $methods, string $path, mixed $handler, array $binds = [], array $opts = [])
 * @method static group(string $prefix, Closure $callback, array $middleware = [], array $opts = [])
 * @method static config(array $config)
 * @method static match(string $path, string $method = 'GET')
 * @method static dispatch(DispatcherInterface | array $dispatcher, $path = null, $method = null)
 */
final class SRouter
{
    /** @var Router|RouterInterface */
    private static $router;

    /**
     * SRouter constructor. disable new class.
     */
    private function __construct()
    {
    }

    /**
     * Defines a route callback and method
     *
     * @param string $method
     * @param array  $args
     *
     * @return Router|mixed
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public static function __callStatic($method, array $args)
    {
        if (method_exists(self::getRouter(), $method)) {
            return self::getRouter()->$method(...$args);
        }

        throw new InvalidArgumentException("call invalid method: $method");
    }

    /**
     * @return Router|RouterInterface
     */
    public static function getRouter(): RouterInterface
    {
        if (!self::$router) {
            self::$router = new Router();
        }

        return self::$router;
    }

    /**
     * @param RouterInterface $router
     */
    public static function setRouter(RouterInterface $router): void
    {
        self::$router = $router;
    }
}
