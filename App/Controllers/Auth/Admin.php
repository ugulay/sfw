<?php

namespace App\Controllers\Auth;

use App\Models\Account;
use Kernel\Controller;
use Kernel\Response;
use Kernel\Session;
use Kernel\Validation;
use Kernel\View;

class Admin extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = Session::getInstance();
    }

    public function index()
    {
        $view = new View();
        $view->render('Front/Auth/admin');
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
            return Response::redirect('/auth/admin');
        }

        $username = $validation->getInput('email');
        $password = $validation->getInput('password');
        $password = hash_hmac("sha512",$password,$_ENV["SECRET_KEY"]);

        $where = ["AND" => [
            "email" => $username,
            "password" => $password,
            "status" => 2
        ]];

        $user = new Account;

        $get = $user->checkLogin($where);
        
        if (!$get) {
            Session::flash('error', __('auth.loginFailed'));
            return Response::redirect('/auth/admin');
        }

        $data = $user->getAccount($where);

        Session::set('auth', true);
        Session::set('adminAuth', true);
        Session::set('user', $data);

        return Response::redirect('/admin');

    }

    public function logout()
    {
        Session::del('adminAuth');
        Session::del('user');
        return Response::redirect('/auth/admin');
    }

}
