<?php

namespace Kernel;

class Input
{

    public static function method()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        return $method;
    }

    public static function json($key = null, $default = null)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!array_key_exists($data[$key])) {
            return $default;
        }

        return $data[$key] ? $data[$key] : $default;
    }

    public static function get($key = null, $default = null)
    {
        if ($_GET && array_key_exists($_GET[$key])) {
            return $_GET[$key];
        }

        return $default;
    }

    public static function post($key = null, $default = null)
    {
        if ($_POST && array_key_exists($_POST[$key])) {
            return $_POST[$key];
        }

        return $default;
    }

    public static function file($key = null)
    {
        if ($_FILES && array_key_exists($_FILES[$key])) {
            return $_FILES[$key];
        }

        return false;
    }

    public static function cookie($key = null, $default = null)
    {
        if ($_COOKIE && array_key_exists($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }

        return $default;
    }

}
