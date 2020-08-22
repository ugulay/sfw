<?php

use Kernel\JWT;
use Pimple\Container;
use Kernel\Request;
use Kernel\Response;

$container = new Container;

/**
 * START OF CONTAINERS
 */


$container["redis"] = function(){
    global $redisClient;
    return $redisClient;
};

$container["request"] =  new Request;

$container["response"] = new Response;

$container[JWT::class] = new JWT;

/**
 * END OF CONTAINERS
 */

return $container;
