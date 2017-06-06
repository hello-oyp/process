<?php
/**
 * Created by PhpStorm.
 * User: yangping
 * Date: 2017/6/2
 * Time: 上午9:45
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