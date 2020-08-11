<?php

namespace Kernel;

class Helper
{

    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public static function tokenize($len = 32)
    {
        $len = $len / 2;
        $token = openssl_random_pseudo_bytes($len);
        return bin2hex($token);
    }

    public static function __($key = null, $params = [])
    {
        \Kernel\Session::start();
        $lang = \Kernel\Session::get('language');
        $lang = $lang ? $lang : $_ENV["DEFAULT_LANG"];
        $file = ROOT . '/App/Lang/' . $lang . '.php';
        if (!is_readable($file)) {
            \Kernel\Session::set('language', $_ENV["DEFAULT_LANG"]);
            throw new \Exception($file . ' lang file load failed.');
        }
        $lang = require $file;
        if (array_key_exists($key, $lang)) {
            $val = $lang[$key];
            return vsprintf($val, $params);
        }
        return $key;
    }

    public static function old($key = null)
    {
        \Kernel\Session::getInstance();
        return \Kernel\Session::getFlashInput($key);
    }

    public static function sqlTimestamp()
    {
        return date("Y-m-d H:i:s");
    }

    public static function route($name = null, $replace = false)
    {
        $r = \Kernel\Router::getInstance();
        return $r->link($name, $replace);
    }

}
