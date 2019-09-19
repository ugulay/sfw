<?php

namespace Kernel;

use Kernel\Config;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{

    private $mailer = null;
    private $isHTML = true;
    private $sendResult = null;

    public function __construct()
    {
        $cfg = Config::get();
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = $cfg["SMTP_HOST"];
        $this->mailer->SMTPAuth = $cfg["SMTP_AUTH"]; // Enable SMTP authentication
        $this->mailer->Username = $cfg["SMTP_USER"]; // SMTP username
        $this->mailer->Password = $cfg["SMTP_PASS"]; // SMTP password
        $this->mailer->SMTPSecure = $cfg["SMTP_SECURE"]; // Enable TLS encryption, `ssl` also accepted
        $this->mailer->Port = $cfg["SMTP_PORT"]; // TCP port to connect to
        $this->mailer->setFrom($cfg["SMTP_FROM"], $cfg["SMTP_NAME"]);
        $this->mailer->CharSet = 'utf-8';
        $this->mailer->isHTML($this->isHTML);
    }

    public function isHTML($bool = true)
    {
        $this->isHTML($bool);
        return $this;
    }

    public function addAddress($address = null, $name = null)
    {
        if ($name !== null) {
            $this->mailer->addAddress($address, $name);
        }
        $this->mailer->addAddress($address);
        return $this;
    }

    public function addReplyTo($address = null, $name = null)
    {
        if ($name !== null) {
            $this->mailer->addReplyTo($address, $name);
        }
        $this->mailer->addReplyTo($address);
        return $this;
    }

    public function addCC($address = null, $name = null)
    {
        if ($name !== null) {
            $this->mailer->addCC($address, $name);
        }
        $this->mailer->addCC($address);
        return $this;
    }

    public function addBCC($address = null, $name = null)
    {
        if ($name !== null) {
            $this->mailer->addBCC($address, $name);
        }
        $this->mailer->addBCC($address);
        return $this;
    }

    public function addAttachment($path = null, $name = null)
    {
        if ($name !== null) {
            $this->mailer->addAttachment($path, $name);
        }
        $this->mailer->addAttachment($path);
        return $this;
    }

    public function subject($text = null)
    {
        $this->mailer->Subject($text);
        return $this;
    }

    public function body($text = null)
    {
        $this->mailer->Body($text);
        return $this;
    }

    public function altBody($text = null)
    {
        $this->mailer->AltBody($text);
        return $this;
    }

    public function send($debug = false)
    {

        $this->sendResult = $this->mailer->send();

        if ($this->sendResult) {
            return true;
        }

        return false;

    }

}
