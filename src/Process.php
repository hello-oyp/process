<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Process;

class Process extends ProcessAbstract
{
    protected $child;

    protected $num = 1;

    protected $work_child;

    public function add(Child $child, $num = 1)
    {
        $this->child = $child;
        $this->num = $num;
        return $this;
    }

    public function start()
    {
        $this->keep();
    }

    public function keep()
    {
        $this->wait(false);
        $this->clear();
        $num = $this->num - count($this->work_child);
        for ($i = 0; $i < $num; $i++) {
            $this->workChild();
        }
    }

    protected function workChild()
    {
        $pid = $this->fork();
        if ($pid > 0) {

        } else {
            $this->child->start();
            exit(0);
        }
    }

    protected function fork()
    {
        $pid = $this->control->fork();
        if ($pid > 0) {
            $this->work_child[$pid] = time();
        }
        return $pid;
    }

    public function kill($signal)
    {
        foreach ((array)$this->work_child as $pid => $value) {
            $this->child->kill($pid, $signal);
        }
    }

    public function wait($option = true)
    {
        foreach ((array)$this->work_child as $pid => $value) {
            if ($this->child->wait($pid, $option) > 0) {
                unset($this->work_child[$pid]);
            }
        }
    }

    public function clear()
    {
        foreach ((array)$this->work_child as $pid => $value) {
            if (!$this->child->kill($pid, 0)) {
                unset($this->work_child[$pid]);
            }
        }
    }

    public function terminate($option = true)
    {
        $this->clear();
        $this->kill(SIGTERM);
        $this->wait(true);
    }

}
