<?php declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\RouteTest;

use Guirong\Route\Helper\RouteHelper;
use PHPUnit\Framework\TestCase;

/**
 * Class RouteHelperTest
 * @package Guirong\RouteTest
 */
class RouteHelperTest extends TestCase
{
    public function testIsStaticRoute(): void
    {
        $ret = RouteHelper::isStaticRoute('/abc');
        $this->assertTrue($ret);

        $ret = RouteHelper::isStaticRoute('/hi/{name}');
        $this->assertFalse($ret);

        $ret = RouteHelper::isStaticRoute('/hi/[tom]');
        $this->assertFalse($ret);
    }
}
