<?php

namespace App\Controllers\Admin;

use App\Models\Post;
use Kernel\Controller;
use Kernel\View;

class Index extends Controller
{

    public function index(){

    }

    public function login()
    {
        $view = new View();
        $view->render('Admin/login');
    }

    public function checkLogin()
    {
        $view = new View();
        $view->render('Admin/login');
    }

}
