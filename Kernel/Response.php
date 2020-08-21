<?php

namespace Kernel;

use Kernel\View;

class Response
{

    public $_code = 200;
    public $_data;
    public $session;
    public $inputs = [];

    function code(int $code = 200)
    {
        http_response_code($code);
        $this->_code = $code;
        return $this;
    }

    function setData($key = null, $value = null)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    function json()
    {
        $jsonEncoded = json_encode($this->getData());
        echo $jsonEncoded;
        return $this;
    }

    function getData($key = null)
    {
        if ($key !== null) {
            return $this->_data[$key];
        }
        return $this->_data;
    }

    function showPage($file = null)
    {
        $view = new View();
        $view->render($file);
        return $this;
    }

    function withInputs($inputs = [])
    {
        $this->inputs = $inputs;
        \Kernel\Session::flashInputs($inputs);
    }

    public static function redirect($location = null)
    {
        if ($location !== null) {
            return header('Location : ' . $location, true, 301);
        }
    }
}
