<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Process;

use Yp\Process\Control\Info;
use Yp\Process\Control\Signal;

class Control
{
    protected $is_running = false;

    protected $signal;

    public function __construct()
    {
        $this->signal = new Signal();
    }

    public function __invoke()
    {
        if (!$this->isRunning()) {
            $this->start();
        }
        return $this->loop();
    }

    public function loop()
    {
        $this->signal->signalDispatch();
        return $this->isRunning();
    }

    public function start()
    {
        $this->is_running = true;
        $this->signal->setHandle('interrupt', array($this, 'stop'));
        $this->signal->setHandle('quit', array($this, 'stop'));
        $this->signal->setHandle('terminate', array($this, 'stop'));
    }

    public function stop()
    {
        $this->is_running = false;
    }

    public function isRunning()
    {
        return $this->is_running;
    }

    public function waitPid($pid, &$status = null, $option = true)
    {
        return Info::waitPid($pid, $status, $option);
    }

    public function getSignal()
    {
        return $this->signal;
    }

    public function sendSignal($pid, $signal)
    {
        return $this->signal->sendSignal($pid, $signal);
    }


    public function fork()
    {
        $pid = pcntl_fork();
        if ($pid == -1) {
            throw new \Exception("fork process is error");
        }
        return $pid;
    }

    public function daemon()
    {
        umask(0);
        $pid = $this->fork();
        if ($pid > 0) {
            exit(0);
        } else {

        }
        $pid = $this->fork();
        if ($pid > 0) {
            exit(0);
        } else {

        }
        $sid = posix_setsid();
        if ($sid < 0) {
            return false;
        }
        umask(0);
        return true;
    }
}