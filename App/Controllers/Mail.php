<?php

namespace App\Controllers;

use Kernel\Controller;
use Kernel\Mailer;
use Kernel\View;

class Mail extends Controller
{

    private static function mailInstance()
    {
        return new Mailer;
    }

    public static function sendActivationMail($code = null, $address = null)
    {

        $view = new View;
        $view->var('code', $code);
        $body = $view->render('Mail/activation', true);

        $mailer = self::mailInstance();
        return $mailer->addAddress($address)
            ->subject(__('mail.activationTitle'))
            ->body($body)
            ->send();

    }

}
