<?php

namespace App\Middlewares;

use Kernel\Middleware;
use Kernel\Response;
use Kernel\Session;

class Admin extends Middleware
{

    private $session;

    public function handler()
    {

        $route = route();
        $this->session = new Session();
        if(!$this->session::has('auth') && $route->getUri() != 'admin/login'){
            header('Location: /admin/login' );
            return false;
        }

        return true;

    }
}
