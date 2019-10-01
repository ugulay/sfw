<?php

namespace Kernel;

use FastRoute;
use ReflectionClass;
use ReflectionMethod;

class Router
{

    private static $instance = null;

    private $_request, $_raw, $_uri, $_method = false;

    private $fastRoute;
    private $groupData;
    private $middleware = [];
    private $currentMiddleware;
    private $currentRoute;
    private $routes;
    private $named;

    public function __construct()
    {
        $this->fastRoute = new FastRoute\RouteCollector(
            new FastRoute\RouteParser\Std(),
            new FastRoute\DataGenerator\GroupCountBased()
        );
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

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

    public function bind($options, $cb)
    {
        $this->groupData = $options;
        call_user_func($cb);
        $this->groupData = null;
    }

    public function getRoutes()
    {
        return $this->fastRoute->getData();
    }

    public function getNamed($name = null, $key = 'uri')
    {

        if (!$this->named) {
            return null;
        }

        $named = $this->named;

        if ($name == null) {
            return $named;
        }

        if ($name !== null && isset($named[$name])) {
            return $named[$name][$key];
        }

        return null;

    }

    public function addRoute($method, $uri, $action, $options = null)
    {

        if ($this->groupData) {
            $data = $this->groupData;
            $uri = $this->groupData['prefix'] . '/' . $uri;
        }

        $uri = $this->fixRoute($uri);

        if ($options != null) {
            $data = array_merge($data, $options);
        }

        $data['action'] = $action;
        $data['current'] = false;

        $this->addNamed($data, $method, $uri);

        $this->fastRoute->addRoute($method, $uri, $data);
    }

    public function addNamed($data, $method, $uri)
    {
        if (isset($data['name'])) {
            $this->named[$data['name']] = [
                'method' => $method,
                'uri' => $uri,
                'namespace' => $data['namespace'],
                'action' => $data['action'],
                'full' => !is_callable($data['action']) ? $data['namespace'] . '\\' . $data['action'] : null,
                'middleware' => $data['middleware'],
                'prefix' => $data['prefix'],
                'current' => false,
            ];
        }
    }

    public function setNamed($name, $key, $val)
    {
        if (isset($this->named[$name])) {
            $this->named[$name][$key] = $val;
        }
    }

    /**
     * Fix the given route
     */
    public function fixRoute($route): string
    {
        return trim(preg_replace('/\/{2,}/', '/', $route), '/');
    }

    public function setCurrentRoute($routeData)
    {
        $this->currentRoute = $routeData;
    }

    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }

    /**
     * Dispatch
     */
    public function dispatch()
    {

        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->_raw = $this->_uri = urldecode($_SERVER['REQUEST_URI']);
        $this->_request = parse_url($this->_raw);
        $this->_uri = $this->fixRoute($this->_request['path']);

        return $this->handleRoute(
            $this->_method,
            $this->_uri,
            $this->fastRoute->getData()
        );

    }

    private function handleRoute($method, $uri, $routeData)
    {

        $dispatcher = new FastRoute\Dispatcher\GroupCountBased($routeData);

        $routeInfo = $dispatcher->dispatch($method, $uri);

        switch ($routeInfo[0]) {

            case FastRoute\Dispatcher::FOUND:

                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                $this->setCurrentRoute($routeInfo);
                $this->setNamed($handler['name'], 'current', true);

                $action = $handler['action'];

                $this->currentMiddleware = $handler['middleware'];

                $middlewareControl = $this->next();

                if ($middlewareControl === true) {

                    if (is_callable($action)) {
                        return call_user_func_array($action, $vars);
                    }

                    if (is_string($action) && !is_callable($action)) {
                        return $this->runClass($handler, $method, $vars);
                    }

                }

                if ($middlewareControl !== true) {
                    return $middlewareControl;
                }

                throw new \Exception('An error accquired. Route handler is not correct.');

                break;
            case FastRoute\Dispatcher::NOT_FOUND:
                $this->throwError();
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $this->throwError('Front/error403', 403);
                break;
        }

    }

    public function getMiddleware()
    {
        return $this->currentMiddleware;
    }

    private function next()
    {

        $middleware = $this->getMiddleware();

        if (!class_exists($middleware) && !empty($middleware)) {
            throw new \Exception($middleware . ' middleware not found.');
        }

        if ($middleware == null || $middleware == false) {
            return true;
        }

        $mw = new $middleware;
        $res = $mw->handle();

        return false;

    }

    public function route($name, $key = 'uri')
    {
        return $this->getNamed($name, $key);
    }

    public function link($name, $replace = false)
    {
        $get = $this->route($name, 'uri');
        if ($replace !== false) {
            return preg_replace('/(\{.*?\})/', $get, $replace);
        }
        return $get;
    }

    private function runClass($handler, $method, $vars)
    {

        $controller = $handler['action'];
        $namespace = $handler['namespace'];
        $method = 'index';

        if (strpos($controller, '@') !== false) {
            list($controller, $method) = explode('@', $controller);
        }

        $controller = $namespace . '\\' . $controller;

        if (!class_exists($controller)) {
            throw new \Exception($controller . ' class not found.');
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
