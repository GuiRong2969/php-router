<?php declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\RouteTest;

use Guirong\Route\PreMatchRouter;
use Guirong\Route\RouterManager;
use PHPUnit\Framework\TestCase;

/**
 * Class RouterManagerTest
 * @package Guirong\RouteTest
 * @covers \Guirong\Route\RouterManager
 */
class RouterManagerTest extends TestCase
{
    /** @var RouterManager */
    private $manager;

    protected function setUp(): void
    {
        $configs = [
            'default'   => 'main-site',
            'main-site' => [
                'driver'     => 'default',
                'conditions' => [
                    'domains' => ['abc.com', 'www.abc.com']
                ],
            ],
            'doc-site'  => [
                'driver'     => 'cached',
                'options'    => [

                ],
                'conditions' => [
                    'domains' => 'doc.abc.com'
                ],
            ],
            'blog-site' => [
                'driver'     => 'preMatch',
                'options'    => [
                    'path'   => '/test',
                    'method' => 'GET',
                ],
                'conditions' => [
                    'schemes' => 'http',
                    'domains' => 'blog.abc.com'
                ],
            ],
        ];

        $this->manager = new RouterManager($configs);
    }

    public function testGet(): void
    {
        $router = $this->manager->get([
            'scheme' => 'http',
            'domain' => 'blog.abc.com',
        ]);

        $this->assertSame('blog-site', $router->getName());
        $this->assertInstanceOf(PreMatchRouter::class, $router);
    }

    public function testGetByName(): void
    {
        $router = $this->manager->getByName('blog-site');

        $this->assertSame('blog-site', $router->getName());
    }

    public function testGetDefault(): void
    {
        $router = $this->manager->getDefault();

        $this->assertSame('default', $router->getName());
    }
}
