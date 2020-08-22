<?php

namespace Kernel;

use \Kernel\Traits\Singleton;

class Session
{

    use Singleton;

    public function __construct()
    {
        self::start();
    }

    public static function start()
    {
        if (!headers_sent() && !session_id()) {
            if (session_start()) {
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
        if (!isset($_SESSION)) {
            throw new \Exception('Session not started');
        }
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
        if ($data == null) {
            $get = self::get($key);
            self::del($key);
            return $get;
        }
        return self::set($key, $data);
    }

    public static function flashInputs($inputs = [])
    {
        self::set('inputs', $inputs);
    }

    public static function getFlashInput($key = null)
    {
        $inputs = self::get('inputs');
        $get = null;
        if (is_array($inputs) && array_key_exists($key, $inputs)) {
            $get = $inputs[$key];
            unset($_SESSION['inputs'][$key]);
        }
        return $get;
    }
}
