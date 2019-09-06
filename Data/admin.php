<?php

return [
    ['GET', '', '\App\Controllers\Index@index'],
    ['GET', 'test', '\App\Controllers\Index@test'],
    ['GET', 'test2/{param}', '\App\Controllers\Index@test2'],
];
