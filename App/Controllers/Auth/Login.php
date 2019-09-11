<?php

namespace App\Controllers\Auth;

use Kernel\Controller;
use Kernel\Session;
use Kernel\View;
use Kernel\Input;
use Kernel\Response;

use App\Models\User;

class Login extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function index()
    {
        $view = new View();
        $view->render('Front/Auth/login');
    }

    public function login()
    {
        $username = Input::post('username');
        $password = Input::post('password');

        $response = new Response();

        if (empty($username) || empty($password)) {
            Session::flash('info', 'Kullanıcı adı veya parola boş olamaz.');
            return $response->redirect('/auth');
        }

        $password = md5($password);

        $where = ['AND' => [
            "username" => $username,
            "password" => $password,
            "status" => 1
        ]];

        $user = new User;
        $get = $user->checkLogin($where);

        if (!$get) {
            Session::flash('info', 'Oturum doğrulanamadı.');
            return $response->redirect('/auth');
        }

        $data = $user->getUser($where);

        Session::set('auth', true);
        Session::set('user', $data["id"]);

        return $response->redirect('/dashboard');

    }

    public function registration()
    {
        $view = new View();
        $view->render('Front/Auth/Register');
    }

    public function register()
    {
        
    }

    public function logout()
    {

        Session::del('auth');
        Session::del('user');

        $response = new Response();
        return $response->redirect('/login');
    }

}
