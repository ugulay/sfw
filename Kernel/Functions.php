<?php

function __($key = null, $params = [])
{
    return Kernel\Helper::__($key, $params);
}

function old($key = null)
{
    return Kernel\Helper::old($key);
}

function route($key = null,$replace = false)
{
    return Kernel\Helper::route($key,$replace);
}