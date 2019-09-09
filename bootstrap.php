<?php

define('ROOT', __DIR__);
define('DS', DIRECTORY_SEPARATOR);
define('_DATA', ROOT . '/Data/');
define('DEV', true);

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

/**
 * Autoloader
 */
global $_autoloader;
$_autoloader = require ROOT . '/Kernel/vendor/autoload.php';
$_autoloader->addPsr4('', ROOT . '/');

/**
 * Config Loader
 */
global $_config;
$_config = new Kernel\Config();
$_config->source('config');
$_config->parse();

/**
 * Global functions
 */
require(ROOT . '/Kernel/Functions.php');

/**
 * Router
 */
global $_router;
$_router = new Kernel\Router();
$_router->setRoutes('', 'web');
$_router->setRoutes('api', 'api');
$_router->setRoutes('admin', 'admin', ['middleware' => '\App\Middlewares\Admin']);
$_router->dispatch();
