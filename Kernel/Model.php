<?php

namespace Kernel;

use Kernel\Database;
use Kernel\Interfaces\IModel;

abstract class Model implements IModel
{

    protected static $conn;

    const TABLE = null;

    final public static function DB()
    {

        if (self::$conn) {
            return self::$conn;
        }

        return self::$conn = Database::getInstance();
    }

}
