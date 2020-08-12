<?php

namespace Kernel;

use Kernel\Session;
use Kernel\Traits\Singleton;

class Language
{

    use Singleton;

    function setCurrentLang(string $language): string
    {
        Session::set("language", $language);
        return $language;
    }

    function getCurrentLang(): string
    {
        $lang = Session::get("language");
        $lang = $lang ? $lang : $_ENV["DEFAULT_LANG"];
        return $lang;
    }

    function __($key = null, $params = []): string
    {
        $lang = $this->getCurrentLang();
        $file = ROOT . "/App/Lang/" . $lang . ".php";
        if (!is_readable($file)) {
            $this->session::set("language", $_ENV["DEFAULT_LANG"]);
            throw new \Exception($file . " lang file load failed.");
        }
        $lang = require $file;
        if (array_key_exists($key, $lang)) {
            $val = $lang[$key];
            return vsprintf($val, $params);
        }
        return $key;
    }
}
