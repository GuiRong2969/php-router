<?php

declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\PhpRouter;

use BadMethodCallException;
use Closure;
use InvalidArgumentException;

/**
 * Class Router - This is object version
 * @package Guirong\PhpRouter
 */
class RouteRegistrar
{
    /**
     * The router instance.
     *
     * @var \Guirong\PhpRouter\Router
     */
    protected $router;

    /**
     * The attributes to pass on to the router.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The methods to dynamically pass through to the router.
     *
     * @var array
     */
    protected $passthru = [
        'get', 'post', 'put', 'patch', 'delete', 'head', 'connect', 'options', 'any', 'map', 'add', 'group', 'groupBuilder'
    ];

    /**
     * The attributes that can be set through this class.
     *
     * @var array
     */
    protected $allowedAttributes = [
        'middleware',
    ];

    /**
     * Create a new route registrar instance.
     *
     * @param  \Guirong\PhpRouter\Router  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Set the value for a given attribute.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function attribute($key, $value)
    {
        if (!in_array($key, $this->allowedAttributes)) {
            throw new InvalidArgumentException("Attribute [{$key}] does not exist.");
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Register a new route with the router.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return \Guirong\PhpRouter\Route
     */
    protected function registerRoute($method, $parameters)
    {
        $routerHandle = $this->router->withMiddleware($this->attributes['middleware'])->{$method}(...$parameters);
        $this->router->resettingMiddleware();
        return $routerHandle;
    }

    /**
     * Dynamically handle calls into the route registrar.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Guirong\PhpRouter\Route|$this
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, $this->passthru)) {
            return $this->registerRoute($method, $parameters);
        }

        if (in_array($method, $this->allowedAttributes)) {
            if ($method === 'middleware') {
                return $this->attribute($method, !empty($parameters) ? (is_array($parameters[0]) ? $parameters[0] : $parameters) : []);
            }

            return $this->attribute($method, $parameters[0]);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }
}
