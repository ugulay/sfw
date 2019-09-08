<?php

namespace Kernel;

use Kernel\View;
use Kernel\Session;

class Response
{

    private $_code = 200;
    private $_data;
    private $session;

    public function code($code = 200)
    {
        http_response_code($code);
        $this->_code = $code;
        return $this;
    }

    public function setData($key = null, $value = null)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    public function json()
    {
        $jsonEncoded = json_encode($this->getData());
        echo $jsonEncoded;
        return $this;
    }

    private function getData($key = null)
    {
        if ($key !== null) {
            return $this->_data[$key];
        }
        return $this->_data;
    }

    public function showPage($file = null)
    {
        $view = new View();
        $view->render($file);
        return $this;
    }

    public function redirect($location = null)
    {
        if ($location !== null) {
            header('Location : ' . $location);
            return $this;
        }
    }

}
