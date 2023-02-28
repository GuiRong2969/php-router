<?php declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\RouteTest\Controllers;

/**
 * Class DemoController
 * @package Guirong\RouteTest\Controllers
 */
class DemoController
{
    public function indexAction(): void
    {
        echo 'hello, this is ' . __METHOD__ . '<br>';
    }

    public function testAction(): void
    {
        echo 'hello, this is ' . __METHOD__ . '<br>';
    }

    // you can access by '/demo/oneTwo' or '/demo/one-two'
    public function oneTwoAction(): void
    {
        echo 'hello, this is ' . __METHOD__ . '<br>';
    }
}
