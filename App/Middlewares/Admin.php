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
        $route = $request->router;
        $this->session = Session::getInstance();
        if (!$this->session::has('adminAuth')) {
            header('Location: ' . Helper::route('auth.adminLogin'));
            return false;
        }
        return true;

    }
}
