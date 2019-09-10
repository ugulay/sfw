<?php

return [
    ['GET', '', '\App\Controllers\Index@index'],
    ['GET', '/language', '\App\Controllers\Index@language'],
    ['GET', '/login', '\App\Controllers\Login@index'],
    ['GET', '/logout', '\App\Controllers\Login@logout'],
    ['POST', '/login/check', '\App\Controllers\Login@check'],
    ['GET', '/post/{param}', '\App\Controllers\Post@show'],
];
