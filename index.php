<?php

define('ROOT', __DIR__);
define('DS', DIRECTORY_SEPARATOR);
define('_DATA', ROOT . '/Data/');
define('DEV', true);

use Kernel\Bootstrap;
use Kernel\Config;
use Kernel\Request;
use Kernel\Response;
use Kernel\Session;
use Pimple\Container;

ob_start();

/**
 * Debug Mode
 */
if (DEV) {
    error_reporting(E_ALL);
    ini_set('error_reporting', E_ALL);
} else {
    error_reporting(0);
    ini_set('error_reporting', 0);
}

require ROOT . '/Kernel/Autoload.php';
require ROOT . '/Kernel/Functions.php';
require _DATA . 'routes.php';

$session = Session::getInstance();

$config = new Config;
$config->source('config');
$config->parse();

global $container;

$container = new Container();

$container['Config'] = $config->get();

$container['Session'] = $session;

$container['Request'] = function () {
    return new Request;
};

$container['Response'] = function () {
    return new Response;
};

$app = new Bootstrap($container);
$app->boot();
