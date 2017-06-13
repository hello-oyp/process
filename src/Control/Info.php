<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Process\Control;

class Info
{
    public static function getId()
    {
        return posix_getpid();
    }

    public static function getPid()
    {
        return posix_getppid();
    }

    public static function waitPid($pid, &$status = null, $option = true)
    {
        return pcntl_waitpid($pid, $status, $option ? 0 : WNOHANG);
    }
}