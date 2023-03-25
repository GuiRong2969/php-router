<?php

declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\PhpRouter\Middleware;

class Config
{
    /**
     * The application's middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        // \Guirong\PhpRouter\Middleware\Example\AMiddleware::class,
        // \Guirong\PhpRouter\Middleware\Example\BMiddleware::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // 'alias-c' => \Guirong\PhpRouter\Middleware\Example\CMiddleware::class,
        // 'alias-d' => \Guirong\PhpRouter\Middleware\Example\DMiddleware::class,
        // 'alias-e' => \Guirong\PhpRouter\Middleware\Example\EMiddleware::class,
    ];

    /**
     * Get the application's route middleware groups.
     *
     * @return array
     */
    final public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * Get the application's route middleware.
     *
     * @return array
     */
    final public function getRouteMiddleware(): array
    {
        return $this->routeMiddleware;
    }
}
