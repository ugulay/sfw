<?php

use Kernel\Router;

$r = Router::getInstance();

/**
 * DEFAULT
 */
$r->bind([
    'prefix' => '',
    'middleware' => '',
    'namespace' => '\App\Controllers',
], function () use ($r) {
    $r->addRoute('GET', '', 'Index@index', ['name' => 'main.index']);
    $r->addRoute('GET', 'language', 'Index@language', ['name' => 'main.language']);

    $r->addRoute('GET', 'contact', 'Contact@index', ['name' => 'app.contact']);
    $r->addRoute('POST', 'send', 'Contact@sendMail');
});

/**
 * AUTH
 */
$r->bind([
    'prefix' => 'auth',
    'middleware' => '',
    'namespace' => '\App\Controllers\Auth',
], function () use ($r) {
    $r->addRoute('GET', '', 'Login@index', ['name' => 'auth.login']);
    $r->addRoute('POST', '', 'Login@login', ['name' => 'auth.loginAction']);
    $r->addRoute('GET', 'registration', 'Register@registration', ['name' => 'auth.register']);
    $r->addRoute('POST', 'registration', 'Register@register', ['name' => 'auth.registerAction']);
    $r->addRoute('GET', 'activation/{code}/', 'Register@activation', ['name' => 'auth.registerCode']);
    $r->addRoute('GET', 'admin', 'Admin@index', ['name' => 'auth.adminLogin']);
    $r->addRoute('POST', 'admin', 'Admin@login');
    $r->addRoute('GET', 'logout', 'Login@logout', ['name' => 'auth.logout']);
});

/**
 * ADMIN
 */
$r->bind([
    'prefix' => 'admin',
    'middleware' => '\App\Middlewares\Admin',
    'namespace' => '\App\Controllers\Admin',
], function () use ($r) {
    $r->addRoute('GET', '', 'Index@index', ['name' => 'admin.index']);
    
    $r->addRoute('GET', 'menu', 'Menu@index', ['name' => 'admin.menuIndex']);
    $r->addRoute('POST', 'menu/add', 'Menu@add', ['name' => 'admin.menuAdd']);

});