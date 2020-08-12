<?php

namespace Kernel\Traits;

trait ToArray {

    function __toArray() {
        return json_decode(json_encode(get_object_vars($this)), true);
    }

}
