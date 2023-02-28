<?php declare(strict_types=1);
/**
 * User: Rong Gui
 */

namespace Guirong\RouteTest\Controllers;

/**
 * Class RestController
 * @package Guirong\RouteTest\Controllers
 */
class RestController
{
    public function indexAction(): void
    {
        echo __METHOD__ . PHP_EOL;
    }

    public function viewAction(): void
    {
        echo __METHOD__ . PHP_EOL;
    }

    public function createAction(): void
    {
        echo __METHOD__ . PHP_EOL;
    }

    public function updateAction(): void
    {
        echo __METHOD__ . PHP_EOL;
    }

    public function patchAction(): void
    {
        echo __METHOD__ . PHP_EOL;
    }

    public function deleteAction(): void
    {
        echo __METHOD__ . PHP_EOL;
    }
}
