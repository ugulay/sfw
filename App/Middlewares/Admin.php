<?php

namespace App\Middlewares;

use Kernel\Helper;
use Kernel\Middleware;
use Kernel\Session;

class Admin extends Middleware
{

    public function handle($request)
    {

        if (!$request->session::has('adminAuth')) {
            header('Location: /' . Helper::route('auth.adminLogin'));
            return false;
        }

        return true;

    }
}
