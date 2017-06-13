<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Process\Action;

use Evenement\EventEmitter;

class Action extends EventEmitter
{
    protected $callback;

    const EVENT_INIT = 16;

    const EVENT_START = 32;

    const EVENT_SUCCESS = 64;

    const EVENT_ERROR = 128;

    public function __construct(callable $callable)
    {
        $this->callback = $callable;
    }

    public function bind($event, $handle)
    {
        $this->on($event, $handle);
    }

    public function trigger($event)
    {
        $this->emit($event);
    }

    public function execute($args = [])
    {
        return call_user_func_array($this->callback, $args);
    }
}
