<?php

namespace App\Controllers;

use Kernel\Controller;
use Kernel\Mailer;
use Kernel\View;

class Mail extends Controller
{

    public static function sendActivationMail($code = null, $address = null)
    {

        $view = new View;
        $view->var('code', $code);
        $body = $view->render('Mail/activation', true);

        $mailer = new Mailer;
        $mailer->addAddress($address)->subject(__('mail.activationTitle'))->body($body)->send();

    }

}
