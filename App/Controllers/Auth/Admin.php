<?php

namespace App\Controllers\Auth;

use App\Models\User;
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
        $this->session = app('Session');
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
        $password = md5($password);

        $where = ['AND' => [
            "email" => $username,
            "password" => $password,
            "status" => 1,
            "root" => 1,
        ]];

        app('User', function () {
            return new User;
        });

        $get = app('User')->checkLogin($where);

        if (!$get) {
            Session::flash('error', __('auth.loginFailed'));
            return Response::redirect('/auth/admin');
        }

        $data = app('User')->getUser($where);

        Session::set('adminAuth', true);
        Session::set('user', $data);

        return Response::redirect('/admin/dashboard');

    }

    public function logout()
    {
        Session::del('adminAuth');
        Session::del('user');
        return Response::redirect('/auth/admin');
    }

}
