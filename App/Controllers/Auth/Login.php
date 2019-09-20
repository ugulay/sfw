<?php

namespace App\Controllers\Auth;

use App\Models\User;
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
        $this->session = new Session();
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
        $password = md5($password);

        $where = ['AND' => [
            "email" => $username,
            "password" => $password,
            "status" => 1,
        ]];

        $user = new User;
        $get = $user->checkLogin($where);

        if (!$get) {
            Session::flash('error', __('auth.loginFailed'));
            return Response::redirect('/auth');
        }

        $data = $user->getUser($where);

        Session::set('auth', true);
        Session::set('user', $data["id"]);

        return Response::redirect('/dashboard');

    }

    public function registration()
    {
        $view = new View();
        $view->render('Front/Auth/Register');
    }

    public function register()
    {

        $validation = new Validation;

        $validation->rules('post', [
            "name" => ['required', 'string', 'min' => 2],
            "surname" => ['required', 'string', 'min' => 2, 'max' => 10],
            "email" => ['required', 'email', 'unique' => 'users.email'],
            "password" => ['required'],
            "password2" => ['required', 'same' => 'password'],
        ], [
            "name" => __('auth.name'),
            "surname" => __('auth.surname'),
            "email" => __('auth.emailAddress'),
            "password" => __('auth.password'),
            "password2" => __('auth.passwordRepeat'),
        ]);

        $validation->validate();

        if ($validation->fails()) {
            Session::flash('error', $validation->getFails());
            Session::flashInputs($validation->getInputs());
            return Response::redirect('/auth/registration');
        }

        $data = $validation->getInputs();

        $user = new User;
        $res = $user->insert($data);
        
        

    }

    public function logout()
    {
        Session::del('auth');
        Session::del('user');
        return Response::redirect('/auth');
    }

}
