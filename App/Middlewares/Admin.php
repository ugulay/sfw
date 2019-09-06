<?php

namespace App\Middlewares;

use Kernel\Middleware;

class Admin extends Middleware
{

    public function handler()
    {

        if ($_GET['asd']) {
            return true;
        }

        return false;

    }
}
