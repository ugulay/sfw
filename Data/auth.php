<?php

return [
    ['GET', '/', '\App\Controllers\Auth\Login@index'],
    ['POST', '/', '\App\Controllers\Auth\Login@login'],
    ['GET', '/registration', '\App\Controllers\Auth\Login@registration'],
    ['POST', '/registration', '\App\Controllers\Auth\Login@register'],
    ['GET', '/admin', '\App\Controllers\Auth\Login@admin'],
    ['POST', '/admin', '\App\Controllers\Auth\Login@adminLogin'],
    ['GET', '/logout', '\App\Controllers\Auth\Login@logout'],
];
