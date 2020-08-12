<?php

define("ROOT", __DIR__);
define("DS", DIRECTORY_SEPARATOR);
define("_DATA", ROOT . "/Data/");
define("DEV", true);

use Kernel\Bootstrap;
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
 * Helper and Routes
 */
require ROOT . "/Kernel/Helper.php";
require _DATA . "routes.php";


$container = new Container();

$container['Session'] = Session::getInstance();

$container['Request'] = function () {
    return new Request;
};

$container['Response'] = function () {
    return new Response;
};

$app = new Bootstrap($container);
$app->boot();
