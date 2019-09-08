<?php

namespace Kernel;

class Session
{

    public function __construct()
    {
        self::start();
    }

    public static function start()
    {
        if (!headers_sent() && !session_id()) {
            if (session_start()) {
                session_regenerate_id();
                return true;
            }
        }
        return false;
    }

    public static function set($key = '', $value = null)
    {
        $_SESSION[$key] = $value;
    }

    public static function has($key = '')
    {
        return array_key_exists($key, $_SESSION);
    }

    public static function get($key)
    {
        if (self::has($key)) {
            return $_SESSION[$key];
        }
        return false;
    }

    public static function del($key)
    {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        if (isset($_SESSION)) {
            session_destroy();
        }
    }

    public static function dump()
    {
        if (isset($_SESSION)) {
            print_r($_SESSION);
            return;
        }

        throw new \Exception("Session is not initialized");
    }

    public static function flash($key = null, $data = null)
    {
        self::set($key, $data);
    }

    public static function getFlash($key)
    {
        if (self::has($key)) {
            $data = self::get($key);
            self::del($key);
            return $data;
        }
    }

}