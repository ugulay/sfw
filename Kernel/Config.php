<?php

namespace Kernel;

use Kernel\Storage;

class Config
{

    public static $envFile = null;
    protected static $config;

    public function source($file)
    {
        $storage = new Storage();
        $storage->source(_DATA . $file . '.php');
        self::$envFile = $storage->getInclude();
    }

    public function parse()
    {
        $kw = self::$envFile;
        foreach ($kw as $key => $val) {
            putenv("$key=$val");
            $_SERVER[$key] = $val;
            $_ENV[$key] = $val;
            self::$config[$key] = $val;
        }
    }

    public static function get($key = null)
    {
        return $key === null ? self::$config : self::$config[$key];
    }

    public static function env($key = null)
    {
        return $key === null ? $_ENV : $_ENV[$key];
    }

    public static function server($key = null)
    {
        return $key === null ? $_SERVER : $_SERVER[$key];
    }

}
