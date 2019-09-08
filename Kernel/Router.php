<?php

namespace Kernel;

use FastRoute;
use ReflectionClass;
use ReflectionMethod;

class Router
{

    private $_request, $_raw, $_uri, $_routes, $_routeOptions, $_currentMiddleware, $_method = false;

    public function getRequest()
    {
        return $this->_request;
    }

    public function getRaw()
    {
        return $this->_raw;
    }

    public function getUri()
    {
        return $this->_uri;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function getMiddleware()
    {
        return $this->_currentMiddleware;
    }

    /**
     * Set Route via File and Prefix
     */
    public function setRoutes($prefix = null, $routeFile = false, $options = []): bool
    {

        $file = _DATA . $routeFile . '.php';

        if (file_exists($file) && is_readable($file)) {
            $this->_routes[$prefix] = require $file;
            $this->_routeOptions[$prefix] = $options;
            return true;
        }

        throw new \Exception($file . 'no route file found.');

        return false;
    }

    /**
     * Route List
     */
    private function getRoutes(): array
    {
        return $this->_routes;
    }

    /**
     * Fix the given route
     */
    private function fixRoute($route): string
    {
        return trim(preg_replace('/\/{2,}/', '/', $route), '/');
    }

    /**
     * Dispatch
     */
    public function dispatch()
    {

        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->_raw = $this->_uri = $_SERVER['REQUEST_URI'];
        $this->_request = parse_url($this->_raw);
        $this->_uri = $this->fixRoute($this->_request['path']);

        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            foreach ($this->getRoutes() as $prefix => $routes) {
                $prefix = strlen($prefix) ? $prefix . '/' : $prefix;
                foreach ($routes as $route) {
                    $route[1] = $this->fixRoute($prefix . $route[1]);
                    $r->addRoute($route[0], $route[1], $route[2]);
                }
            }
        });

        $routeInfo = $dispatcher->dispatch($this->_method, $this->_uri);

        return $this->handleRoute($routeInfo);

    }

    /**
     * Guard works like middleware, this is wh i called middleware, i just love them =)
     */
    private function checkMiddleware()
    {
        $explode = $this->fixRoute($this->_uri);
        $explode = explode('/', $this->_uri);
        $first = $explode[0];

        if (!isset($this->_routeOptions[$first])) {
            return false;
        }

        $currMiddleware = $this->_routeOptions[$first];
        $this->_currentMiddleware = $currMiddleware;

        if ($first && $this->_routes[$first] && $currMiddleware['middleware']) {

            $class = $currMiddleware['middleware'];

            if (!class_exists($class)) {
                throw new \Exception($class . 'middleware class not found.');
            }

            $mw = new $class();
            $mwRes = $mw->handler();

            if ($mwRes === true) {
                return true;
            }

        }
    }

    private function handleRoute($routeInfo)
    {

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::FOUND:

                $this->checkMiddleware();

                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                if (is_callable($handler)) {
                    return call_user_func_array($handler, $vars);
                }

                if (is_string($handler) && !is_callable($handler)) {

                    $controller = $handler;
                    $method = 'index';

                    if (strpos($handler, '@') !== false) {
                        list($controller, $method) = explode('@', $handler);
                    }

                    return $this->runClass($controller, $method, $vars);
                }

                throw new \Exception('An error accqauired. Route handler is not correct.');

                break;
            case FastRoute\Dispatcher::NOT_FOUND:
                $this->throwError();
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $this->throwError('Front/error403', 403);
                break;
        }

    }

    private function runClass($controller, $method, $vars)
    {

        if (!class_exists($controller)) {
            throw new \Exception($controller . ' Class not found.');
        }

        $rc = new ReflectionClass($controller);

        $class = $rc->newInstance();

        if ($rc->hasMethod($method)) {
            $rm = new ReflectionMethod($class, $method);
            return $rm->invokeArgs($class, $vars);
        }

        throw new \Exception($method . ' action not found.');
    }

    private function throwError($page = 'Front/error404', $code = 404)
    {
        $response = new Response();
        return $response->code($code)->showPage($page);
    }
}
