<?php

namespace Kernel;

use Kernel\Input;
use Kernel\Response;
use Kernel\Router;

use Kernel\Interfaces\IRequest;

class Request implements IRequest
{

    public $router;
    public $session;
    public $inputs;
    public $containers;

    function getInputs()
    {
        return Input::all();
    }

    function dispatch($router, $containers)
    {

        $this->containers = $containers;

        $this->router = $router;
        $this->router->setContainers($this->containers);

        $routeInfo = $this->router->dispatch();

        $response = new Response;

        switch ($routeInfo) {
            case Router::METHOD_NOT_ALLOWED:

                $response->code(403)->showPage('Front/error403');
                break;

            case Router::NOT_FOUND:
                $response->code(404)->showPage('Front/error404');
                break;

            default:
                $response = $this->handle($routeInfo);
                break;
        }

        if ($response instanceof Response) {
            return $response;
        } else {
            echo (string) $response;
        }
    }

    function next($obj)
    {

        if (!empty($this->middleware)) {
            if (!(new $this->middleware())->handle($obj)) {
                return false;
            }
        }

        $action = $this->controllerAction;
        return $action($obj);
    }

    function handle($routeInfo)
    {

        $this->method = $routeInfo[0];
        $this->vars = $routeInfo[2];
        $this->middleware = $routeInfo[1]['middleware'];
        $this->session = Session::getInstance();
        $this->inputs = $this->getInputs();

        $this->controllerAction = function () use ($routeInfo) {
            return $this->handleRoute($routeInfo);
        };

        return $this->next($this);
    }

    function handleRoute($handler)
    {

        $action = $handler[1]['action'];

        if (!is_callable($action)) {
            $res = $this->router->runClass($handler[1], $this->method, $this->vars);
        } else {
            $res = call_user_func_array($action, $this->vars);
        }

        return $res;
    }
}
