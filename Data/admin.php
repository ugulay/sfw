<?php

return [
    ['GET', '', '\App\Controllers\Admin\Index@index'],
    ['GET', 'login', '\App\Controllers\Admin\Index@login'],
    ['POST', 'loginCheck', '\App\Controllers\Admin\Index@loginCheck'],
];
