<?php

namespace Kernel\Interfaces;

interface IRequest
{
    function dispatch($router, $containers);
    function next($object);
    function handle($object);
}
