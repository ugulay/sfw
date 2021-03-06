<?php

namespace App\Controllers\Auth;

use App\Models\Account;
use Kernel\Controller;
use Kernel\Response;
use Kernel\Session;
use Kernel\Validation;
use Kernel\View;

class Login extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = Session::getInstance();
    }

    public function index()
    {
        $view = new View();
        $view->render('Front/Auth/login');
    }

    public function login()
    {

        $validation = new Validation;

        $validation->rules('post', [
            "email" => ['required', 'email'],
            "password" => ['required'],
        ], [
            "email" => __('auth.emailAddress'),
            "password" => __('auth.password'),
        ]);

        $validation->validate();

        Session::flashInputs($validation->getInputs());

        if ($validation->fails()) {
            Session::flash('error', $validation->getFails());
            return Response::redirect('/auth');
        }

        $username = $validation->getInput('email');
        $password = $validation->getInput('password');
        $password = hash_hmac("sha512",$password,$_ENV["SECRET_KEY"]);

        $where = ['AND' => [
            "email" => $username,
            "password" => $password,
            "status" => 1,
        ]];

        $user = new Account;
        $get = $user->checkLogin($where);

        if (!$get) {
            Session::flash('error', __('auth.loginFailed'));
            return Response::redirect('/auth');
        }

        $data = $user->getAccount($where);

        Session::set('auth', true);
        Session::set('user', $data);

        return Response::redirect('/dashboard');

    }

    public function logout()
    {
        Session::del('auth');
        Session::del('user');
        return Response::redirect('/auth');
    }

}
