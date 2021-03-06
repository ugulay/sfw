<?php

namespace App\Controllers;

use App\Models\Post as PostModel;
use Kernel\Controller;
use Kernel\Response;
use Kernel\Slugger;
use Kernel\View;

class Post extends Controller
{

    public function show($slug = null)
    {

        $data = $this->getPostDetail($slug);

        if (!$data) {
            $response = new Response();
            return $response->code(404)->showPage('Front/error404');
        }

        $view = new View();
        $view->var('data', $data);
        $view->render('Front/post');
    }

    public function getPostDetail($slug)
    {
        $result = [];
        $data = new PostModel;
        $result = $data->getPost(['status' => 1, 'slug' => $slug], $start = 0, $limit = 10);
        return $result;
    }
}
