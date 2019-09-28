<?php

namespace Kernel;

use FastRoute;
use ReflectionClass;
use ReflectionMethod;

class Router
{

    private $_request, $_raw, $_uri, $_method = false;
    static $_named, $_routes = [];

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

    /**
     * Set Route via File and Prefix
     */
    public static function bind($options, $route)
    {
        self::$_routes[] = [$options, $route];
    }

    /**
     * Route List
     */
    private function getRoutes(): array
    {
        return self::$_routes;
    }

    /**
     * Fix the given route
     */
    private function fixRoute($route): string
    {
        return trim(preg_replace('/\/{2,}/', '/', $route), '/');
    }

    public static function named($name = null, $col = 'route')
    {
        $arr = self::$_named;

        $get = array_key_exists($name, $arr);

        if (!$get) {
            return false;
        }

        if ($col == false) {
            return $arr[$name];
        }

        return $arr[$name][$col];
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

        $allRoutes = $this->getRoutes();

        $dispatcher = FastRoute\simpleDispatcher(

            function (FastRoute\RouteCollector $r) use ($allRoutes) {

                foreach ($allRoutes as $routesArr) {

                    $options = $routesArr[0];

                    $prefix = strlen($options['prefix']) ? $options['prefix'] . '/' : $options['prefix'];
                    $namespace = strlen($options['namespace']) ? $options['namespace'] : '';

                    $routes = $routesArr[1];

                    foreach ($routes as $route) {

                        $route[1] = $this->fixRoute($prefix . $route[1]);

                        $name = isset($route[3]) && array_key_exists('name', $route[3]) ? $route[3]['name'] : false;

                        if ($name) {
                            self::$_named[$name] = [
                                'route' => $route[1],
                                'handler' => $namespace . $route[2],
                                'method' => $route[0],
                                'options' => $options,
                            ];
                        }

                        $r->addRoute($route[0], $route[1], $namespace . $route[2]);

                    }

                }

            }

        );
        print_r(self::$_named);

        $routeInfo = $dispatcher->dispatch($this->_method, $this->_uri);

        return $this->handleRoute($routeInfo);

    }

    private function handleRoute($routeInfo)
    {

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::FOUND:

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

                throw new \Exception('An error accquired. Route handler is not correct.');

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
