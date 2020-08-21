<?php

namespace Kernel;

final class Bootstrap
{

    public $router;
    public $container;

    public function __construct($router, $container)
    {
        $this->router = &$router;
        $this->container = &$container;
    }

    public function boot()
    {

        //Request Container from services.php
        $request = $this->container["request"];

        if (method_exists($request, "dispatch")) {
            return $request->dispatch($this->router, $this->container);
        }

        throw new \Exception("Dispatcher not found on Requst::class");
    }
}
