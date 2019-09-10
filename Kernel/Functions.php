<?php

function slugify($text)
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

function tokenize($len = 32)
{
    $len = $len / 2;
    $token = openssl_random_pseudo_bytes($len);
    return bin2hex($token);
}

function route()
{
    global $_router;
    return $_router;
}

function __($key = null, $params = [])
{
    \Kernel\Session::start();
    $lang = \Kernel\Session::get('language');
    $lang = $lang ? $lang : \Kernel\Config::get('DEFAULT_LANG');
    $file = ROOT . '/App/Lang/' . $lang . '.php';
    if (!is_readable($file)) {
        \Kernel\Session::set('language', \Kernel\Config::set('DEFAULT_LANG'));
        throw new \Exception($file . ' lang file load failed.');
    }
    $lang = require $file;
    if (array_key_exists($key, $lang)) {
        $val = $lang[$key];
        return vsprintf($val, $params);
    }
    return $key;
}
