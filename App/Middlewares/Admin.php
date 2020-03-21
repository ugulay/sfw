<?php

namespace App\Middlewares;

use Kernel\Helper;
use Kernel\Middleware;
use Kernel\Session;

class Admin extends Middleware
{

    private $session;

    public function handle($request)
    {

        if (!$request->session::has('adminAuth2')) {
            header('Location: /' . Helper::route('auth.adminLogin'));
            return false;
        }

        return true;

    }
}
