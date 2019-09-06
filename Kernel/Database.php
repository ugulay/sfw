<?php

namespace Kernel;

use Kernel\Config;
use Medoo\Medoo;

final class Database
{

    protected static $instance = null;

    protected function __construct()
    {}
    protected function __clone()
    {}
    protected function __wakeup()
    {}

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = self::connect();
        }
        return static::$instance;
    }

    public static function connect()
    {

        $cfg = Config::get();

        if ($cfg['DATABASE_TYPE'] == 'sqlite') {
            return new Medoo([
                "database_type" => $cfg['DATABASE_TYPE'],
                "database_file" => _DATA . '/' . $cfg['DATABASE_FILE'],
            ]);
        }

        if ($cfg['DATABASE_TYPE'] == 'mysql') {
            return new Medoo([
                "database_type" => $cfg['DATABASE_TYPE'],
                "database_name" => $cfg['DATABASE_NAME'],
                "charset" => 'utf8',
                "server" => $cfg['DATABASE_HOST'],
                "username" => $cfg['DATABASE_USER'],
                "password" => $cfg['DATABASE_PASS'],
            ]);
        }

        return false;

    }

}
