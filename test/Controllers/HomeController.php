<?php declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\RouteTest\Controllers;

/**
 * Class HomeController
 * @package Guirong\Route\example\controllers
 */
class HomeController
{
    public function indexAction(): void
    {
        echo 'hello, this is ' . __METHOD__ . '<br>';
    }

    public function testAction(): void
    {
        echo 'hello, this is ' . __METHOD__ . '<br>';
    }

    public function aboutAction(): void
    {
        echo 'hello, this is about page';
    }
}
