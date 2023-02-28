<?php

declare(strict_types=1);
/**
 * User: Rong Gui
 * Date: 2022/11/18
 * Time: 下午8:03
 */

namespace Guirong\PhpRouter\Dispatcher;

use Guirong\PhpRouter\Route;
use Throwable;
use Guirong\PhpRouter\Ioc;
use Guirong\PhpRouter\RouterInterface;
use InvalidArgumentException;
use LogicException;
use RuntimeException;

/**
 * Class Dispatcher
 * 相比 SimpleDispatcher，支持更多的自定义选项控制
 *
 * @package Guirong\PhpRouter\Dispatcher
 */
class Dispatcher
{
    /*******************************************************************************
     * route callback handler dispatch
     ******************************************************************************/

    public const FAV_ICON = '/favicon.ico';

    // some route events

    public const ON_NOT_FOUND          = 'notFound';

    public const ON_METHOD_NOT_ALLOWED = 'methodNotAllowed';

    /** @var RouterInterface */
    private $router;

    /** @var bool */
    private $initialized;

    /**
     * some setting for self
     *
     * @var array
     */
    protected $options = [
        // Filter the `/favicon.ico` request.
        'filterFavicon' => false,

        // default action method name
        'defaultAction' => 'index',

        // enable dynamic action.
        // e.g
        // if set True;
        //  $router->any('/demo/{act}', App\Controllers\Demo::class);
        //  you access '/demo/test' will call 'App\Controllers\Demo::test()'
        'dynamicAction'    => false,

        // @see Router::$globalParams['act']
        'dynamicActionVar' => 'act',
    ];

    /**
     * Class constructor.
     *
     * @param RouterInterface|null $router
     * @param array                $options
     *
     * @throws LogicException
     */
    public function __construct(array $options = [], RouterInterface $router = null)
    {
        $this->initialized = false;
        $this->initOptions($options);

        if ($router) {
            $this->setRouter($router);
        }
    }

    /**
     * @param array $options
     *
     * @throws LogicException
     */
    public function initOptions(array $options): void
    {
        if ($this->initialized) {
            throw new LogicException('Has already started to distributed routing, and configuration is not allowed!');
        }

        foreach ($options as $name => $value) {
            if (isset($this->options[$name])) {
                $this->options[$name] = $value;
            } else {
                // maybe it is a event
                $this->on($name, $value);
            }
        }
    }

    /**
     * Object creator.
     *
     * @param RouterInterface|null $router
     * @param array                $options
     *
     * @return self
     * @throws LogicException
     */
    public static function create(array $options = [], RouterInterface $router = null)
    {
        return new static($options, $router);
    }

    /**
     * Runs the callback for the given path and method.
     *
     * @param string      $path
     * @param null|string $method
     *
     * @return mixed
     * @throws Throwable
     */
    public function dispatchUri(string $path = '', string $method = '')
    {
        $path = $path ?: $_SERVER['REQUEST_URI'];

        if (strpos($path, '?')) {
            $path = (string)parse_url($path, PHP_URL_PATH);
        }

        // if 'filterFavicon' setting is TRUE
        if ($path === self::FAV_ICON && $this->options['filterFavicon']) {
            return null;
        }

        $method = $method ?: $_SERVER['REQUEST_METHOD'];
        $method = strtoupper($method);

        /** @var Route $route */
        [$status, $path, $route] = $this->router->match($path, $method);

        return $this->dispatch($status, $path, $method, $route);
    }

    /**
     * Dispatch route handler for the given route info.
     * {@inheritdoc}
     *
     * @throws Throwable
     */
    public function dispatch(int $status, string $path, string $method, $route)
    {
        // not found
        if ($status === RouterInterface::NOT_FOUND) {
            return $this->handleNotFound($path, $method);
        }

        // method not allowed. $route is methods array.
        if ($status === RouterInterface::METHOD_NOT_ALLOWED) {
            return $this->handleNotAllowed($path, $method, $route);
        }

        return $this->doDispatch($path, $route);
    }

    /**
     * @param string $path
     * @param string $method
     * @param Route  $route
     *
     * @return bool|mixed|null
     * @throws Throwable
     */
    protected function doDispatch(string $path, $route)
    {
        $result  = null;
        $handler = $route->getHandler();
        $params  = $route->getParams();
        try {
            $result = $this->callHandler($path, $handler, $params);
        } catch (Throwable $e) {
            throw $e;
        }
        return $result;
    }

    /**
     * execute the matched Route Handler
     *
     * @param string         $path    The route path
     * @param callable|mixed $handler The route path handler
     * @param array          $args    Matched param from path
     *                                [
     *                                'name' => value
     *                                ]
     *
     * @return mixed
     * @throws Throwable
     */
    protected function callHandler(string $path, $handler, array $args = [])
    {
        if (is_object($handler)) {
            return $this->handleClosureFunction($handler, $args);
        }
        if (is_array($handler)) {
            $segments = $handler;
        } else if (is_string($handler)) {
            if (strpos($handler, '@') === false && function_exists($handler)) {
                return $this->handleClosureFunction($handler, $args);
            }
            $segments = explode('@', trim($handler));
        } else {
            throw new InvalidArgumentException("Invalid route handler for route '$path'");
        }

        $className = $segments[0];
        if (!empty($segments[1])) {
            $actionName = $segments[1];
            // use dynamic action
        } elseif ($this->options['dynamicAction'] && ($var = $this->options['dynamicActionVar'])) {
            $actionName = isset($args[$var]) ? trim($args[$var], '/') : $this->options['defaultAction'];
            // defined default action
        } elseif (!$actionName = $this->options['defaultAction']) {
            throw new RuntimeException("please config the route path [$path] controller action to call");
        }
        return $this->handleClassFunction($className, $actionName, $args);
    }

    /**
     * Execute Closure function
     *
     * @param callable $handler
     * @param array $params
     * @return mixed
     */
    protected function handleClosureFunction($handler, $params)
    {
        return $handler($params);
    }

    /**
     * Execute class function
     *
     * @param string $className
     * @param string $methodName
     * @param array $params
     * @return mixed
     */
    protected function handleClassFunction($className, $methodName, $params = [])
    {
        if (!method_exists($className, $methodName)) {
            throw new \ReflectionException("method not exist: method $className->$methodName() is not exist!");
        }
        $instance = Ioc::make($className);
        $className = get_class($instance);
        $method = new \ReflectionMethod($className, $methodName);
        $params = array_merge(
            Ioc::getMethodParams($className, $methodName),
            $params
        );
        if ($method->isPublic()) {
            return $method->invokeArgs($instance, $params);
        } else {
            throw new \ReflectionException("method not public: method $className->$methodName() is not public!");
        }
    }

    /**
     * Set output header
     *
     * @return void
     */
    protected static function setOutputHeader()
    {
        $header = [
            'Content-Type' => 'application/json; charset=utf-8'
        ];
        if (!headers_sent() && !empty($header)) {
            http_response_code(200);
            foreach ($header as $name => $val) {
                header($name . ':' . $val);
            }
        }
    }

    /**
     * @param string $path Request uri path
     * @param string $method
     * @param bool   $actionNotExist
     *                     True: The `$path` is matched success, but action not exist on route parser
     *                     False: The `$path` is matched fail
     *
     * @return bool|mixed
     * @throws Throwable
     */
    protected function handleNotFound(string $path, string $method, $actionNotExist = false)
    {
        $handler = $this->getOption(self::ON_NOT_FOUND);
        if($handler && $handler != '/404'){
            $this->callHandler('',$handler);
        }else{
            $this->handleDefaultNotFound($path, $method, $actionNotExist);
        }
    }

    protected function handleDefaultNotFound(string $path, string $method, $actionNotExist = false){
        if($actionNotExist){
            throw new RuntimeException("route error , function $path -> $method not exist");
        }else{
            throw new RuntimeException("route not defined , path:$path");
        }
    }

    /**
     * @param string $path
     * @param string $method
     * @param array  $methods The allowed methods
     *
     * @return mixed
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws Throwable
     */
    protected function handleNotAllowed(string $path, string $method, array $methods)
    {
        $handler = $this->getOption(self::ON_METHOD_NOT_ALLOWED);
        if($handler){
            $this->callHandler('',$handler);
        }else{
            $this->handleDefaultNotAllowed($path, $method, $methods);
        }
    }

    protected function handleDefaultNotAllowed(string $path, string $method, array $methods){
        throw new RuntimeException("route error , $method method not allowed");
    }
    

    /**
     * Defines callback on happen event
     *
     * @param          $event
     * @param callable $handler
     */
    public function on(string $event, $handler): void
    {
        if (self::isSupportedEvent($event)) {
            $this->options[$event] = $handler;
        }
    }

    /**
     * @param string $name
     * @param        $value
     */
    public function setOption(string $name, $value): void
    {
        $this->options[$name] = $value;
    }

    /**
     * @param string $name
     * @param null   $default
     *
     * @return mixed|null
     */
    public function getOption(string $name, $default = null)
    {
        return $this->options[$name] ?? $default;
    }

    /**
     * @return array
     */
    public static function getSupportedEvents(): array
    {
        return [
            self::ON_NOT_FOUND,
            self::ON_METHOD_NOT_ALLOWED,
        ];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public static function isSupportedEvent(string $name): bool
    {
        return in_array($name, static::getSupportedEvents(), true);
    }

    /**
     * @return bool
     */
    public function hasRouter(): bool
    {
        return $this->router !== null;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param array $options
     * [
     *     'domains'  => [ 'a-domain.com', '*.b-domain.com'],
     *     'schemes' => ['https'],
     * ]
     */
    protected function validateMetadata(array $options): void
    {
        // 1. validate Schema

        // 2. validate validateDomains
        // $serverName = $_SERVER['SERVER_NAME'];

        // 3. more something ...
    }
}
