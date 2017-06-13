<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Process\Control;

class Signal
{
    protected $signals = [
        'about' => SIGABRT,
        'alarm' => SIGALRM,
        'child' => SIGCHLD,
        'continue' => SIGCONT,
        'hangup' => SIGHUP,
        'interrupt' => SIGINT,
        'kill' => SIGKILL,
        'pipe' => SIGPIPE,
        'quit' => SIGQUIT,
        'stop' => SIGSTOP,
        'suspend' => SIGTSTP,
        'terminate' => SIGTERM
    ];

    public function sendSignal($pid, $signal)
    {
        if ($pid == null || $pid == '') {
            $pid = Info::getId();
        }
        return posix_kill($pid, $this->parserSignalHandle($signal));
    }

    protected function parserSignalHandle($signal)
    {
        if (isset($this->signals[$signal])) {
            $signal = $this->signals[$signal];
        } elseif (defined($signal)) {
            $signal = constant($signal);
        }
        if (!is_int($signal)) {
            throw new \InvalidArgumentException("the $signal is not define");
        }
        return $signal;
    }

    public function signalDispatch()
    {
        pcntl_signal_dispatch();
    }

    public function setHandle($signal, $handle)
    {
        $signal = $this->parserSignalHandle($signal);
        if (pcntl_signal($signal, $handle, false)) {
            return true;
        }
        throw new \InvalidArgumentException('the signal handle is not define');
    }
}