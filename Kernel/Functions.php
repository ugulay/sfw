<?php

function __($key = null, $params = [])
{
    return Kernel\Helper::__($key, $params);
}

function old($key = null)
{
    return Kernel\Helper::old($key);
}

function app($key = null, $value = null)
{
    global $container;

    if ($key === null && $value === null) {
        return $container;
    }

    if ($value === null) {
        return $container[$key];
    }

    return $container[$key] = $value;

}
