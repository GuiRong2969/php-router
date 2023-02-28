<?php declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\RouteTest;

use Guirong\Route\CachedRouter;
use Guirong\Route\Route;
use PHPUnit\Framework\TestCase;
use function file_exists;
use function Guirong\Route\createCachedRouter;
use function unlink;

/**
 * Class CachedRouterTest
 * @package Guirong\RouteTest
 */
class CachedRouterTest extends TestCase
{
    public function testCacheRouter(): void
    {
        $cacheFile = __DIR__ . '/routes-cache.php';

        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }

        $config   = [
            'cacheFile'   => $cacheFile,
            'cacheEnable' => 1,
        ];
        $callback = function (CachedRouter $router) {
            $router->get('/path0', 'handler0');
        };

        $router = createCachedRouter($callback, $config);

        $this->assertFalse($router->isCacheLoaded());
        $this->assertTrue($router->isCacheExists());

        /** @var Route $route */
        [$sts, , $route] = $router->match('/path0');

        $this->assertSame(CachedRouter::FOUND, $sts);
        $this->assertSame('/path0', $route->getPath());

        // create again, will load caches.
        $router = createCachedRouter($callback, $config);

        $this->assertTrue($router->isCacheExists());
        $this->assertTrue($router->isCacheLoaded());

        /** @var Route $route */
        [$sts, , $route] = $router->match('/path0');

        $this->assertSame(CachedRouter::FOUND, $sts);
        $this->assertSame('/path0', $route->getPath());

        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }
}
