<?php
/**
 * Created by PhpStorm.
 */
require_once __DIR__ . '/../vendor/autoload.php';

$action = new \Yp\Process\Action\Action(function ($control, $context) {
    while ($control()) {
        echo '--1--';
        sleep(1);
    }
});

$child = new \Yp\Process\Child($action);

$process = new \Yp\Process\Process();

$process->add($child, 2);

$process->start();

$process->running();