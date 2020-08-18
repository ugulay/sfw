<?php

function __(string $key = null, $params = []): string
{
    return (\Kernel\Language::getInstance())->__($key, $params);
}

function old(string $key)
{
    return \Kernel\Session::getFlashInput($key);
}

function slugify($text): string
{
    return \Kernel\Slugger::slugify($text);
}


function randomize(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces[] = $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

function routeLink($name = null, $replace = false): string
{
    $r = \Kernel\Router::getInstance();
    return $r->link($name, $replace);
}
