<?php

namespace App\Controllers\Admin;

use Kernel\Controller;
use Kernel\View;

class Index extends Controller
{

    public function index()
    {
        $view = new View();
        $view->render('Admin/dashboard');
    }

}
