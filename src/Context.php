<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Process;

/**
 * Class Context
 * @package Yp\Process
 * @property string start_time
 * @property string end_time
 */
class Context
{
    protected $data;

    public function __construct($config = [])
    {
        foreach ($config as $key => $value) {
            $this->__set($key, $value);
        }
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }
}