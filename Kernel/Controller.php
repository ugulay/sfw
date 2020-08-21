<?php

namespace Kernel;

use Kernel\Interfaces\IController;

abstract class Controller implements IController
{

    public $containers;

    public function getContainer($container)
    {
        try {
            return $this->containers[$container] ?? false;
        } catch (\Exception $e) {
            throw new \Exception($container . " not found.");
        }
    }
}
