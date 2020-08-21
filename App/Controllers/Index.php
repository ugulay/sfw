<?php

namespace App\Controllers;

use Kernel\Controller;
use Kernel\Input;
use Kernel\View;

class Index extends Controller
{

    function index()
    {
        $view = new View();
        $view->render('Front/index');
    }

    function language(\Kernel\Language $lang)
    {
        $language = Input::get('key');
        if (is_readable(ROOT . '/App/Lang/' . $language . '.php')) {
            $lang->setCurrentLang($language);
        }
    }
}
