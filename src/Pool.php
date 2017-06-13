<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Process;

class Pool extends ProcessAbstract
{
    protected $child_collection;

    protected $process_collection;

    public function __construct()
    {
        $this->child_collection = new \SplObjectStorage();
        parent::__construct();
    }

    public function add(Child $child, $num = 1)
    {
        $child->getContext()->num = $num;
        $this->child_collection->attach($child);
    }

    public function start()
    {
        foreach ($this->child_collection as $child) {
            $child_name = $child->getContext()->hash_name;
            if (isset($this->process_collection[$child_name])
                && $this->process_collection[$child_name] instanceof ProcessAbstract
            ) {
                continue;
            }
            $this->process_collection[$child_name] = $process = new Process();
            $process->add($child, $child->getContext()->num)->start();
        }
    }

    public function keep()
    {
        foreach ((array)$this->process_collection as $process) {
            $process->keep();
            usleep(200);
        }
    }

    public function kill($signal)
    {
        foreach ((array)$this->process_collection as $process) {
            $process->kill($signal);
            usleep(200);
        }
    }

    public function terminate($option = true)
    {
        foreach ((array)$this->process_collection as $process) {
            $process->terminate($option);
            usleep(200);
        }
    }
}
