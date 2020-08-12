<?php

namespace Kernel\Traits;

trait ToJson {

    function __toJson() {
        return json_encode(get_object_vars($this));
    }

}
