#!/usr/bin/php

<?php

define("ROOT", __DIR__);
define("DS", DIRECTORY_SEPARATOR);
define("_DATA", ROOT . "/Data/");
define("DEV", true);

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

if (PHP_SAPI === 'cli' && $argc) {

    $pattern = "/\-{1,2}(\S+)[=:](\S+)/";

    array_shift($argv);
    $handler = $argv[0];
    array_shift($argv);

    $params = [];
    foreach ($argv as $arg) {
        if (preg_match($pattern, $arg, $parse)) {
            $params[$parse[1]] = $parse[2];
        }
    }

    /**
     * Param example
     * php console.php <handler> --<param>=<key> --<param2>=<key2>
     */

    #todo::cli tool will be add soon
    print ("handler : {$handler}") . PHP_EOL;
    print_r($params) . PHP_EOL;
}
