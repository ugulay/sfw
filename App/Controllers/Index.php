<?php

namespace App\Controllers;

use App\Models\Post;
use Kernel\Controller;
use Kernel\Environment;
use Kernel\View;

class Index extends Controller
{

    public function index()
    {

        $data = $this->getLastPost();

        $view = new View();
        $view->var('data', $data);
        $view->render('Front/index');
    }

    public function getLastPost($limit = 10)
    {
        $result = [];
        $data = new Post();
        $result = $data->getPosts(['status' => 1], $start = 0, $limit = 10);
        return $result;
    }

}
