<?php

namespace Kernel;

class View
{

    protected $_ext = '.php';
    protected $_dir = ROOT . '/Theme/';
    public $_vars, $_extend, $_blocks, $_buffer;

    function var($key = null, $val = null)
    {
        $this->_vars[$key] = $val;
    }

    public function getFile($file): string
    {
        $path = $this->_dir . $file . $this->_ext;

        if (!is_readable($path)) {
            throw new \Exception($path . ' view file not found.');
        }

        return (string) $path;
    }

    public function returnView($file = null)
    {
        ob_start();
        require $this->getFile($file);
        return ob_get_clean();
    }

    public function extend($file = null)
    {
        return (string) $this->_extend = $file;
    }

    public function get($file = null)
    {
        echo $this->returnView($file);
    }

    public function block($name = null, $data = null)
    {
        $this->_blocks[$name] = $data;
        ob_start();
    }

    public function endBlock($name = null, \Closure $func = null)
    {
        $content = ob_get_clean();
        $this->_blocks[$name] = $content;

        if ($func !== null) {
            $this->_blocks[$name] = $func($this->getBlock($name));
        }

        return $this->_blocks[$name];
    }

    public function placeholder($name = null, $default = null, $return = false)
    {
        $data = $this->getBlock($name);
        $data = $data ? $data : $default;

        if ($return) {
            return (string) $data;
        }

        echo (string) $data;
        return;
    }

    public function getBlock($name = null)
    {
        return $this->_blocks[$name];
    }

    public function getBlocks()
    {
        return $this->_blocks;
    }

    public function setBlocks(array $blocks)
    {
        $this->_blocks = $blocks;
    }

    public function render($file = null, $return = false)
    {

        if ($this->_vars) {
            extract($this->_vars, EXTR_SKIP);
        }

        ob_start();

        require $this->getFile($file);

        if ($this->_extend) {
            require $this->getFile($this->_extend);
        }

        $this->_buffer = ob_get_clean();

        if (!$return) {
            echo $this->_buffer;
            return;
        }

        return $this->_buffer;
    }
}
