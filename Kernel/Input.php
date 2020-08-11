<?php

namespace Kernel;

class Input
{

    public static function method()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        return $method;
    }

    public static function getContent()
    {
        return file_get_contents('php://input');
    }

    public static function uri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function json($key = null, $default = null)
    {
        $data = json_decode(self::getContent(), true);
        if (!isset($data[$key])) {
            return $default;
        }

        return $data[$key] ? $data[$key] : $default;
    }

    public static function get($key = null, $default = null)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        return $default;
    }

    public static function post($key = null, $default = null)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        return $default;
    }

    public static function file($key = null)
    {
        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        }

        return false;
    }

    public static function cookie($key = null, $default = null)
    {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }

        return $default;
    }

    public static function all()
    {
        return $_GET + $_POST + $_FILES;
    }

    public static function env($key = null)
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        return false;
    }
}
