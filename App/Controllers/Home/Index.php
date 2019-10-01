<?php

namespace App\Controllers\Home;

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
        $view->render('Front/index');
    }

    public function language()
    {
        $language = Input::get('key');
        $defaultLang = Config::get('DEFAULT_LANG');
        if (is_readable(ROOT . '/App/Lang/' . $language . '.php')) {
            Session::set('language', $language);
        }

    }

}
