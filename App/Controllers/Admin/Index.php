<?php

namespace App\Controllers\Admin;

use Kernel\Config;
use Kernel\Controller;
use Kernel\Input;
use Kernel\Session;
use Kernel\View;

class Index extends Controller
{

    public function index()
    {
        $view = new View();
        $view->render('Admin/dashboard');
    }

}
