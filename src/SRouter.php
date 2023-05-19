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
 * @method static self create(array $config = [])
 * @method static  __construct(array $config = [])
 * @method static self use($middleware)
 * @method static Router middleware($middleware)
 * @method static Router withMiddleware(array $middleware)
 * @method static Router resettingMiddleware()
 * @method static Route get(string $path,mixed $handler,array $pathParams = [],array $opts = [])
 * @method static Route post(string $path,mixed $handler,array $pathParams = [],array $opts = [])
 * @method static Route put(string $path,mixed $handler,array $pathParams = [],array $opts = [])
 * @method static Route patch(string $path,mixed $handler,array $pathParams = [],array $opts = [])
 * @method static Route delete(string $path,mixed $handler,array $pathParams = [],array $opts = [])
 * @method static Route head(string $path,mixed $handler,array $pathParams = [],array $opts = [])
 * @method static Route options(string $path,mixed $handler,array $pathParams = [],array $opts = [])
 * @method static Route connect(string $path,mixed $handler,array $pathParams = [],array $opts = [])
 * @method static  any(string $path,mixed $handler,array $pathParams = [],array $opts = [])
 * @method static  map(array|string $methods,string $path,callable|string $handler,array $pathParams = [],array $opts = [])
 * @method static Route add(string $method,string $path,$handler,array $pathParams = [],array $opts = [])
 * @method static Route addRoute(Route $route)
 * @method static  group(string $prefix,Closure $callback,array $middleware = [],array $opts = [])
 * @method static  groupBuilder(string $prefix,Closure $callback,array $middleware = [],array $opts = [])
 * @method static array match(string $path,string $method = GET)
 * @method static bool|callable matchAutoRoute(string $path)
 * @method static Response dispatch(DispatcherInterface|array $dispatcher,string $path = '',string $method = '')
 * @method static array getMiddleWareChains(DispatcherInterface|array $dispatcher,string $path = '',string $method = '')
 * @method static string createUri(string $name,array $pathVars = [])
 * @method static  nameRoute(string $name,Route $route)
 * @method static Route|null getRoute(string $name)
 * @method static int count()
 * @method static  each(Closure $func)
 * @method static array getRoutes()
 * @method static array getChains()
 * @method static  getStaticRoutes()
 * @method static Traversable getIterator()
 * @method static string __toString()
 * @method static string toString()
 * @method static  config(array $config)
 * @method static string getName()
 * @method static  setName(string $name)
 * @method static  addGlobalParams(array $params)
 * @method static  addGlobalParam(string $name,string $pattern)
 * @method static array getGlobalParams()
 * @method static array getGlobalOptions()
 * @method static  setGlobalOptions(array $globalOptions)
 * @method static bool isNamespaceUcFirst()
 * @method static  setNamespaceUcFirst(bool $namespaceUcFirst)
 * 
 * @see \Guirong\PhpRouter\Router
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
