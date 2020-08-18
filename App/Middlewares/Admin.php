<?php

namespace App\Middlewares;

use Kernel\Helper;
use Kernel\Middleware;

class Admin extends Middleware
{

    public function handle($request)
    {

        if (!$request->session::has('adminAuth')) {
            header('Location: /' . routeLink('auth.adminLogin'));
            return false;
        }

        return true;
    }
}
