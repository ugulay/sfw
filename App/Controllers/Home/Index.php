<?php

namespace App\Controllers\Home;

use Kernel\Controller;
use Kernel\Input;
use Kernel\Session;
use Kernel\View;

class Index extends Controller
{

    public function index()
    {
        $view = new View();
        $view->render('Front/index');
    }

    public function language()
    {
        $language = Input::get('key');
        $defaultLang = $_ENV['DEFAULT_LANG'];
        if (is_readable(ROOT . '/App/Lang/' . $language . '.php')) {
            Session::set('language', $language);
        }

    }

}
