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
        if (!$this->session::has('auth') && $route->getUri() != 'login') {
            header('Location: /login');
            return false;
        }

        return true;

    }
}
