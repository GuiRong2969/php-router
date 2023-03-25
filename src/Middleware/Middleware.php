<?php

declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\PhpRouter\Middleware;

use Closure;
use Guirong\PhpRouter\App\Ioc;

class Middleware
{
    protected $middlewares = [];

    public function run($request,Closure $handler)
    {
        // 通过中间件
        $run = $this->throughMiddleware($handler, $this->middlewares);
        return $run($request);
    }

    /**
     * 通过中间件 through the middleware
     * @param $handler
     * @param $stack
     * @return \Closure|mixed
     */
    protected function throughMiddleware($handler, $stack)
    {
        // 闭包实现中间件功能 closures implement middleware functions
        foreach (array_reverse($stack) as $key => $middleware) {
            $handler = function ($request) use ($handler, $middleware) {
                if ($middleware instanceof \Closure) {
                    return call_user_func($middleware, $request, $handler);
                } else {
                    $response = Ioc::make($middleware)->handle($request, $handler);
                    return $response;
                }
            };
        }
        return $handler;
    }

    /**
     * 设置中间件 set middleware
     * @param array $middlewares
     */
    public function middleware(array $middlewares)
    {
        $this->middlewares = $middlewares;
        return $this;
    }
}
