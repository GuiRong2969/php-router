<?php declare(strict_types=1);
/**
 * User: Rong Gui
 *
 * you can test use:
 *  php -S 127.0.0.1:5671 example/object.php
 *
 * then you can access url: http://127.0.0.1:5671
 */

use Guirong\PhpRouter\Dispatcher\Dispatcher;
use Guirong\PhpRouter\Router;

require dirname(__DIR__) . '/test/boot.php';

$router = new Router;

// set config
$router->config([
    // 'ignoreLastSlash' => true,
    // 'tmpCacheNumber' => 100,

    // enable autoRoute
    // you can access '/demo' '/admin/user/info', Don't need to configure any route
    'autoRoute' => 1,
    'controllerNamespace' => 'Guirong\RouteTest\Controllers',
]);

$router->get('/routes', function () {
    global $router;
    echo "<pre><code>{$router->__toString()}</code></pre>";
});

$hasRouter = true;
require __DIR__ . '/some-routes.php';

// $router->rest('/rest', RestController::class);

// $router->any('*', function () {
//     echo "This is fallback handler\n";
// });

// var_dump($router);die;

$dispatcher = new Dispatcher([
    'dynamicAction' => true,
    // on notFound, output a message.
    Dispatcher::ON_NOT_FOUND => function ($path) {
        echo "the page $path not found!";
    }
]);

// OR register event by `Dispatcher::on()`
// $dispatcher->on(Dispatcher::ON_NOT_FOUND, function ($path) {
//     echo "the page $path not found!";
// });

/*
method 1

$dispatcher->setRouter($router);
$dispatcher->dispatch();
 */

/*
method 2
 */
$router->dispatch($dispatcher);

/*
method 3

$router->dispatch([
    'dynamicAction' => true,
    Dispatcher::ON_NOT_FOUND => function ($path) {
        echo "the page $path not found!";
    }
]);
 */
