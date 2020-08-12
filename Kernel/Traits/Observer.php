<?php

namespace Kernel\Traits;

trait Observer {

    /**
     * check if $GLOBALS['hook'] isset and is array
     */
    public function __construct() {
        if (!isset($GLOBALS['hook']) && !is_array($GLOBALS['hook'])) {
            return;
        }
    }

    /**
     * save hook function in $GLOBALS['hook']
     * @param String, function
     */
    public function watch($channel, $func) {

        if (!isset($GLOBALS['hook'][$channel])) {

            $GLOBALS['hook'][$channel] = array();
        }

        array_push($GLOBALS['hook'][$channel], $func);
    }

    /**
     * loop through $GLOBALS['hook'] and call hook functions
     * @param String
     */
    private function subscribe($channel) {

        if (isset($GLOBALS['hook'][$channel])) {

            foreach ($GLOBALS['hook'][$channel] as $func) {

                $func();
            }
        }
    }

}
