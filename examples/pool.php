<?php
/**
 * Created by PhpStorm.
 */
require_once __DIR__ . '/../vendor/autoload.php';

$action = new \Yp\Process\Action\Action(function ($control, $context) {
    while ($control()) {
        echo '--1--' . posix_getpid();
        sleep(1);
    }
});

$action1 = new \Yp\Process\Action\Action(function ($control, $context) {
    while ($control()) {
        echo '--2--' . posix_getpid();
        sleep(1);
    }
});

$child = new \Yp\Process\Child($action);
$child1 = new \Yp\Process\Child($action1);

$pool = new \Yp\Process\Pool();

$pool->add($child, 2);
$pool->add($child1, 2);
$pool->start();

$pool->running();