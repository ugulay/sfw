<?php

define("ROOT", __DIR__);
define("DS", DIRECTORY_SEPARATOR);
define("_DATA", ROOT . "/Data/");
define("DEV", true);

use Kernel\Bootstrap;

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

/**
 * Composer Autoloader
 */
require ROOT . "/Kernel/Autoload.php";

/**
 * ENV Loader
 */
$dotenv = Dotenv\Dotenv::createImmutable(ROOT, ".env", true, "UTF-8");
$dotenv->load();

/**
 * Helper
 */
require ROOT . "/Kernel/Helper.php";

/**
 * Routes and Services
 */
global $routes;
$routes = require _DATA . "routes.php";

global $services;
$services = require _DATA . "services.php";

/**
 * Run the application
 */
$app = new Bootstrap(
    $routes,
    $services
);

$app->boot();