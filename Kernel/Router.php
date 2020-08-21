<?php

namespace Kernel;

use FastRoute;
use Kernel\Controller;
use Kernel\Input;
use ReflectionClass;
use ReflectionMethod;


class Router
{

    private $containers;
    private $_request, $_raw, $_uri, $_method = false;
    private $fastRoute, $groupData, $currentRoute, $named;

    const FOUND = 1;
    const NOT_FOUND = 2;
    const METHOD_NOT_ALLOWED = 3;
    const METHOD_DELIMETER = '@';

    function __construct()
    {
        $this->fastRoute = new FastRoute\RouteCollector(
            new FastRoute\RouteParser\Std(),
            new FastRoute\DataGenerator\GroupCountBased()
        );
    }

    function setContainers($containers)
    {
        $this->containers = $containers;
    }

    function bind($options, $cb)
    {
        $this->groupData = $options;
        call_user_func($cb);
        $this->groupData = null;
    }

    function getRoutes()
    {
        return $this->fastRoute->getData();
    }

    function getNamed($name = null, $key = 'uri')
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

    function addRoute($method, $uri, $action, $options = null)
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

        $this->addNamed($data, $method, $uri);

        $this->fastRoute->addRoute($method, $uri, $data);
    }

    function addNamed($data, $method, $uri)
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
            ];
        }
    }

    /**
     * Fix the given route
     */
    function fixRoute($route): string
    {
        return trim(preg_replace('/\/{2,}/', '/', $route), '/');
    }

    function setCurrentRoute($routeData)
    {
        $this->currentRoute = $routeData;
    }

    function getCurrentRoute()
    {
        return $this->currentRoute;
    }

    /**
     * Dispatch
     */
    function dispatch()
    {

        $this->_method = Input::method();
        $this->_raw = $this->_uri = urldecode(Input::uri());
        $this->_request = parse_url($this->_raw);
        $this->_uri = $this->fixRoute($this->_request['path']);

        return $this->match(
            $this->_method,
            $this->_uri,
            $this->fastRoute->getData()
        );
    }

    function match($method, $uri, $routeData)
    {

        $dispatcher = new FastRoute\Dispatcher\GroupCountBased($routeData);

        $routeInfo = $dispatcher->dispatch($method, $uri);

        switch ($routeInfo[0]) {

            case FastRoute\Dispatcher::FOUND:

                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                $this->setCurrentRoute($routeInfo);

                return [$method, $handler, $vars];
                break;

            case FastRoute\Dispatcher::NOT_FOUND:

                return self::NOT_FOUND;
                break;

            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:

                return self::METHOD_NOT_ALLOWED;
                break;
        }
    }

    function getMiddleware()
    {
        $mw = $this->getCurrentRoute();
        return isset($mw[1]['middleware']) ? $mw[1]['middleware'] : null;
    }

    function route($name, $key = 'uri')
    {
        return $this->getNamed($name, $key);
    }

    function link($name, $replace = false)
    {
        $get = $this->route($name, 'uri');
        if ($replace !== false) {
            return preg_replace('/(\{.*?\})/', $get, $replace);
        }
        return $get;
    }

    function runClass($handler, $method, $vars)
    {

        $controller = $handler['action'];
        $namespace = $handler['namespace'];
        $method = 'index';

        if (strpos($controller, self::METHOD_DELIMETER) !== false) {
            list($controller, $method) = explode(self::METHOD_DELIMETER, $controller);
        }

        $controller = $namespace . '\\' . $controller;

        if (!class_exists($controller)) {
            throw new \Exception($controller . ' class not found.');
        }

        if (!class_exists($controller)) {
            throw new \Exception($controller . ' class not found.');
        }

        $rc = new ReflectionClass($controller);
        $class = $this->getControllerClass($rc);

        $class->containers = $this->containers;

        if ($rc->hasMethod($method)) {
            $rm = new ReflectionMethod($class, $method);
            return $rm->invokeArgs($class, $vars);
        }

        throw new \Exception($method . ' action not found.');
    }

    function getControllerClass(ReflectionClass $rc)
    {

        if (!$rc->isSubclassOf(Controller::class)) {
            throw new \Exception($rc->getName() . ' must be extends from Controller:class.');
        }

        if (!$rc->isInstantiable()) {
            throw new \Exception($rc->getName() . ' is not instantiable.');
        }

        $classConstructer = $rc->getConstructor();
        $class = null;

        if (is_null($classConstructer)) {
            $class = $rc->newInstanceWithoutConstructor();
        } else {
            $getConstructerParams = $this->resolveConstructerParams(
                $classConstructer->getParameters()
            );
            $class = $rc->newInstanceArgs($getConstructerParams);
        }

        return $class;
    }

    function resolveConstructerParams($params)
    {
        foreach ($params as &$param) {
            if ($this->containers[$param->getClass()->getName()]) {
                $param = $this->containers[$param->getClass()->getName()];
            }
        }
        return $params;
    }
}
