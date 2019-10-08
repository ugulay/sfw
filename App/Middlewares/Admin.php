<?php

namespace App\Middlewares;

use Kernel\Helper;
use Kernel\Middleware;

class Admin extends Middleware
{

    private $session;

    public function handle($request)
    {

        $this->session = app('Session');

        if (!$this->session::has('adminAuth')) {
            header('Location: /' . Helper::route('auth.adminLogin'));
            return false;
        }

        return true;

    }
}
