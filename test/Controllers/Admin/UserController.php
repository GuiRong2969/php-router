<?php declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\RouteTest\Controllers\Admin;

/**
 * Class UserController
 * @package Guirong\RouteTest\Controllers\Admin
 */
class UserController
{
    public function indexAction(): void
    {
        echo 'hello, this is ' . __METHOD__ . '<br>';
    }

    public function infoAction(): void
    {
        echo 'hello, this is ' . __METHOD__ . '<br>';
    }
}
