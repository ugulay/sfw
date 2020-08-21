<?php

use Kernel\JWT;
use Pimple\Container;
use Kernel\Session;
use Kernel\Request;
use Kernel\Response;

$container = new Container;

/**
 * START OF CONTAINERS
 */

$container["session"] = Session::getInstance();

$container["request"] =  new Request;

$container["response"] = new Response;

$container[JWT::class] = new JWT;

/**
 * END OF CONTAINERS
 */

return $container;
