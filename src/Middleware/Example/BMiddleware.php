<?php

declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\PhpRouter\Middleware\Example;

use Closure;

class BMiddleware
{
    /**
     * Handle an incoming HTTP request.
     *
     * @param  mixed  $request
     * @return Closure $next
     */
    public function handle($request,Closure $next)
    {
        echo 'Through middleware B'.PHP_EOL;
        return $next($request);
    }
}
