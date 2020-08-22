<?php

define("ROOT", __DIR__);
define("DS", DIRECTORY_SEPARATOR);
define("_DATA", ROOT . "/Data/");
define("DEV", true);

use Kernel\Bootstrap;
use Kernel\Session;

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
 * REDIS
 */
if ($_ENV["REDIS_USE"]) {

    global $redisClient;
    $redisClient = new Predis\Client([
        "scheme" => $_ENV["REDIS_SCHEME"],
        "host" => $_ENV["REDIS_HOST"],
        "port" => $_ENV["REDIS_PORT"]
    ], [
        "prefix" => "sessions:"
    ]);

    if ($_ENV["SESSION_HANDLER"] == "redis") {
        $redisSessionHandler = new \Predis\Session\Handler($redisClient);
        $redisSessionHandler->register();
    }
}

Session::start();

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
