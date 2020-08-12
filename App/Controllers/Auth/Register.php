<?php

namespace App\Controllers\Auth;

use App\Models\Account;
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
        $this->session = Session::getInstance();
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
            "username" => ['required', 'string', 'min' => 8],
            "email" => ['required', 'email', 'unique' => 'users.email'],
            "password" => ['required'],
            "password2" => ['required', 'same' => 'password'],
        ], [
            "username" => __('auth.name'),
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
        $data['activation_code'] = randomize(64);
        $data['password'] = hash_hmac("sha512", $data["password"], $_ENV["SECRET_KEY"]);
        $data['status'] = 0;

        $user = new Account;
        $res = $user->addAccount($data);

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
        $user = new Account;

        $where['AND'] = [
            'status' => 0,
            'activation_code' => $code
        ];

        $get = $user->getAccount($where);

        if (!$get) {
            Session::flash('error', __('auth.activationCodeError'));
        } else {
            $user->updateAccount(['status' => 1], $where);
            Session::flash('success', __('auth.activationCodeSuccess'));
        }

        $view = new View();
        $view->render('Front/Auth/Activation');
    }
}
