<?php

declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\PhpRouter\App;

use Closure;
use Exception;
use Guirong\PhpRouter\App\Ioc;
use Guirong\PhpRouter\Middleware\Middleware;

class Application
{
    /**
     * Routing Instance
     *
     * @var \Guirong\PhpRouter\Router
     */
    protected $router;

    /**
     * Middleware Config Instance
     *
     * @var \Guirong\PhpRouter\Middleware\Config
     */
    protected $middleware;

    /**
     * Router instance dispatch function's vailable parameters
     *
     * @var array
     */
    protected $dispatchOptions = [
        'dispatcher' => null,
        'path' => '',
        'method' => ''
    ];

    public function __construct(object $router)
    {
        $this->router = $router;
    }

    /**
     * Handle Execute program
     *
     * @param mixed $request
     * @param string $middlewareConfigClass
     * @return \Guirong\PhpRouter\Response\Response
     */
    public function handle($request, string $middlewareConfigClass = '')
    {
        if (is_object($request)) {
            Ioc::setResidentInstance($request);
        }

        $this->middleware($middlewareConfigClass);

        if ($this->hasMiddleware()) {
            $response = (new Middleware())->middleware(

                $this->getMiddleWareChains()

            )->run($request, $this->dispatchToRouter());
        } else {
            $dispatch = $this->dispatchToRouter();

            $response = $dispatch($request);
        }

        return $response;
    }

    /**
     * Configure middleware
     *
     * @param string $configClass
     * @return void
     */
    public function middleware(string $configClass)
    {
        if ($configClass != '') {
            $this->middleware = Ioc::make($configClass);
        }
        return $this;
    }


    /**
     * Call the terminate method on any terminable middleware.
     *
     * @param  \Core\Request  $request
     * @param  \Guirong\PhpRouter\Response\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        if ($this->hasMiddleware()) {
            $this->terminateMiddleware($request, $response);
        }
    }

    /**
     * Has middleware
     *
     * @return boolean
     */
    protected function hasMiddleware()
    {
        return !is_null($this->middleware);
    }

    /**
     * Get Middleware default chains and route chains
     *
     * @return array
     */
    protected function getMiddleWareChains(): array
    {
        $chains = $this->middleware->getMiddleware();
        $routeChains = $this->middleware->getRouteMiddleware();
        foreach ($this->router->getMiddleWareChains()  as $key) {
            if (isset($routeChains[$key])) {
                $chains[] = $routeChains[$key];
            } else {
                throw new Exception("route middleware '$key' not defined");
            }
        }
        return $chains;
    }

    /**
     * Dispatch To Router
     *
     * @return \Closure
     */
    protected function dispatchToRouter(): Closure
    {
        return function ($request) {
            return $this->router->dispatch(...$this->getAvailableDispathOptions());
        };
    }

    /**
     * Set dispatch function's vailable parameters
     *
     * @param array $options
     * @return $this
     */
    public function setDispathOptions(array $options)
    {
        if ($options) {
            $this->dispatchOptions = array_merge($this->dispatchOptions, $options);
        }
        return $this;
    }

    /**
     * Get Router instance dispatch function's vailable parameters
     *
     * @return array
     */
    protected function getAvailableDispathOptions(): array
    {
        return [
            $this->dispatchOptions['dispatcher'],
            $this->dispatchOptions['path'],
            $this->dispatchOptions['method']
        ];
    }

    /**
     * Call the terminate method on any terminable middleware.
     *
     * @param  \Core\Request  $request
     * @param  \Guirong\PhpRouter\Response\Response  $response
     * @return void
     */
    protected function terminateMiddleware($request, $response)
    {
        $middlewares = $this->getMiddleWareChains();
        foreach ($middlewares as $middleware) {
            if (!is_string($middleware)) {
                continue;
            }

            $instance = Ioc::make($middleware);

            if (method_exists($instance, 'terminate')) {
                $instance->terminate($request, $response);
            }
        }
    }
}
