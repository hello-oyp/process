<?php
/**
 * Created by PhpStorm.
 * User: yangping
 * Date: 2017/6/2
 * Time: 下午2:48
 */

namespace Yp\Process;

abstract class ProcessAbstract
{
    protected $control;

    public function __construct()
    {
        $this->control = new Control();
    }

    abstract public function kill($signal);

    abstract public function keep();

    abstract public function start();

    abstract public function terminate($option = true);

    public function running()
    {
        $control = $this->control;
        $reload = false;
        $control->getSignal()->setHandle(SIGUSR1, function () use (&$reload) {
            $reload = true;
        });
        while ($control()) {
            if ($reload) {
                $this->kill(SIGTERM);
                $reload = false;
            }
            $this->keep();
            sleep(5);
        }
        $this->terminate(true);
    }

    public function reload($pid)
    {
        $this->control->getSignal()->sendSignal($pid, SIGUSR1);
    }

    public function stop($pid)
    {
        while ($this->control->getSignal()->sendSignal($pid, SIGTERM)) {
            echo '.';
            sleep(1);
        }
        echo 'is stop';
    }
}