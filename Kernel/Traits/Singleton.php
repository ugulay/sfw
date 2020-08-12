<?php

namespace Kernel\Traits;

trait Singleton {

    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = new \ReflectionClass(__CLASS__);
            self::$instance = $class->newInstanceArgs(func_get_args());
        }

        return self::$instance;
    }

}
