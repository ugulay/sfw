<?php

namespace Kernel;

use Kernel\Input;
use Kernel\Response;
use Kernel\Router;

class Request
{

    public $router;
    public $session;
    public $inputs;

    public function getInputs()
    {
        return Input::all();
    }

    public function dispatch()
    {

        $this->router = Router::getInstance();
        $routeInfo = $this->router->dispatch();

        $response = $this->handle($routeInfo);

        if ($response instanceof Response) {
            return $response;
        } else {
            echo (string) $response;
        }
    }

    public function next($obj)
    {
        if (!empty($this->middleware)) {
            return (new $this->middleware())->handle($obj);
        } else {
            $action = $this->controllerAction;
            return $action($obj);
        }
    }

    private function handle($routeInfo)
    {

        $this->method = $routeInfo[0];
        $this->vars = $routeInfo[2];
        $this->middleware = $routeInfo[1]['middleware'];

        $this->controllerAction = function () use ($routeInfo) {
            return $this->handleRoute($routeInfo);
        };

        return $this->next($this);
    }

    private function handleRoute($handler)
    {

        $action = $handler[1]['action'];

        if (!is_callable($action)) {
            $res = Router::runClass($handler[1], $this->method, $this->vars);
        } else {
            $res = call_user_func_array($action, $this->vars);
        }

        return $res;
    }

}
