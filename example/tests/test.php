<?php declare(strict_types=1);
/**
 * User: Rong Gui
 * Date: 2018/8/30
 * Time: 上午11:43
 */

preg_match('#^/gqmqskj/([^/]+)$#', '/gqmqskj/3name3', $m);

var_dump($m);

preg_match('#(?:^/gqmqskj/([^/]+)$)#', '/gqmqskj/3name3', $m);

var_dump($m);
