<?php

namespace App\Middlewares;

use Kernel\Middleware;
use Kernel\Session;

class Admin extends Middleware
{

    private $session;

    public function handle()
    {

        $route = router();
        $this->session = Session::getInstance();
        if (!$this->session::has('adminAuth')) {
            header('Location: ' . route('auth.adminLogin'));
            return false;
        }
        return true;

    }
}
