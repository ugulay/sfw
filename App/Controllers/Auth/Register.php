<?php

namespace App\Controllers\Auth;

use App\Models\User;
use Kernel\Controller;
use Kernel\Response;
use Kernel\Session;
use Kernel\Validation;
use Kernel\View;

class Register extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = app('Session');
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
        unset($data['password2']);
        $data['activation_code'] = tokenize(64);
        $data['password'] = md5(trim($data['password']));
        $data['status'] = 0;

        app('User', function () {
            return new User;
        });
        $res = app('User')->addUser($data);

        if ($res) {
            $mail = \App\Controllers\Mail::sendActivationMail($data['activation_code'], $data["email"]);
        }

        if ($res && $mail) {
            Session::flash('success', __('auth.registerSuccess'));
        } else {
            Session::flash('error', __('auth.registerFail'));
        }

        return Response::redirect('/auth/registration');

    }

    public function activation($code = null)
    {
        app('User', function () {
            return new User;
        });

        $where['AND'] = [
            'status' => 0,
            'activation_code' => $code,
            'activation_at' => null,
        ];

        $get = app('User')->getUser($where);

        if (!$get) {
            Session::flash('error', __('auth.activationCodeError'));
        } else {
            app('User')->updateUser(['status' => 1, 'activation_at' => sqlTimestamp()], $where);
            Session::flash('success', __('auth.activationCodeSuccess'));
        }

        $view = new View();
        $view->render('Front/Auth/Activation');

    }

}
