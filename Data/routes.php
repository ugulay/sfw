<?php

use Kernel\Router;

/**
 * DEFAULT
 */
Router::bind([
    'middleware' => '',
    'name' => 'default',
    'prefix' => '',
    'namespace' => '\App\Controllers\\',
],
    [
        ['GET', '', 'Index@index'],
        ['GET', '/language', 'Index@language'],
    ]
);

/**
 * AUTH
 */
Router::bind([
    'middleware' => '',
    'name' => 'auth',
    'prefix' => 'auth',
    'namespace' => '\App\Controllers\Auth\\',
],
    [
        ['GET', '/', 'Login@index'],
        ['POST', '/', 'Login@login'],
        ['GET', '/registration', 'Register@registration', ['name' => 'auth.register']],
        ['POST', '/registration', 'Register@register'],
        ['GET', '/activation/{code}/', 'Register@activation'],
        ['GET', '/admin', 'Admin@index'],
        ['POST', '/admin', 'Admin@login'],
        ['GET', '/logout', 'Login@logout'],
    ]
);
