<?php

return [
    ['GET', '', '\App\Controllers\Index@index'],
    ['GET', '/post/{param}', '\App\Controllers\Post@show'],
];
