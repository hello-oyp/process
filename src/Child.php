<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Process;

use Yp\Process\Action\Action;

class Child
{
    protected $action;

    protected $context;

    protected $control;

    public function __construct(Action $action, $child_name = '')
    {
        $this->context = new Context();
        $this->action = $action;
        $this->control = new Control();
        if ($child_name == '') {
            $child_name = spl_object_hash($this);
        }
        $this->context->hash_name = (string)$child_name;
        $this->action->trigger(Action::EVENT_INIT, array($this->control, $this->context));
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getControl()
    {
        return $this->control;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function start()
    {
        $this->context->start_time = time();
        $this->run();
        $this->context->end_time = time();
    }

    public function run()
    {
        try {
            $this->action->execute(array($this->control, $this->context));
        } catch (\Exception $exception) {

        }
    }

    public function wait($pid, $option = true)
    {
        $status = 0;
        return $this->control->waitPid($pid, $status, $option);
    }

    public function kill($pid, $signal)
    {
        return $this->control->sendSignal($pid, $signal);
    }

}
