<?php

namespace Kernel;

use Pimple\Container;

class Bootstrap
{

    private $request;
    private $result;
    private $container;

    static $app;

    public function __construct(Container $container)
    {
        $this->container = &$container;
        self::$app = $this->container;
    }

    public function add($name = null, $data = null)
    {
        self::$app[$name] = $data;
    }

    private function make()
    {

        $this->request = self::$app['Request'];

        $handle = $this->request->dispatch(self::$app);

        $this->result = $handle;
    }

    public function boot()
    {
        $this->make();
        return $this->result;
    }

}
