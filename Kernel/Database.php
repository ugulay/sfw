<?php

namespace Kernel;

final class Database
{

    protected static $instance = null;

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = self::connect();
        }
        return static::$instance;
    }

    public static function connect()
    {

        return new \Medoo\Medoo([
            "database_type" => $_ENV["DATABASE_TYPE"],
            "database_name" => $_ENV["DATABASE_NAME"],
            "charset" => "utf8",
            "server" => $_ENV["DATABASE_HOST"],
            "username" => $_ENV["DATABASE_USER"],
            "password" => $_ENV["DATABASE_PASS"]
        ]);
    }
}
