<?php

namespace Kernel;

class Storage
{

    private $file = null;

    public function source($file)
    {
        $this->file = $file;
    }

    public function get()
    {

        if (!file_exists($this->file)) {
            throw new \Exception($this->file . ' file not found.');
        }

        if (!is_readable($this->file)) {
            throw new \Exception($this->file . ' file not readable.');
        }

        return file_get_contents($this->file);
    }

    public function move($dir = null)
    {

        if (empty($dir) || is_null($dir)) {
            throw new \Exception('Directory name can not be blank.');
        }

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return rename($this->file, $dir);
    }

    public function save($data = null)
    {

        if (!is_dir($data)) {
            mkdir($data, 0755, true);
        }

        return file_put_contents($this->file, $data);
    }

    public function append($data = null)
    {

        if (!is_dir($data)) {
            mkdir($data, 0755, true);
        }

        return file_put_contents($this->file, $data, FILE_APPEND);
    }

    public function rename($name = null)
    {

        $dirArray = explode('/', $this->file);
        $fileName = array_pop($dirArray);
        $currentDir = implode('/', $dirArray);

        return rename($this->file, $currentDir . '/' . $name);
    }

    public function copy($name = null)
    {
        if (!file_exists($this->file)) {
            throw new \Exception($this->file . ' file not found.');
        }

        return copy($this->file, $name);
    }

    public function getInclude()
    {
        return require $this->file;
    }

    public function remove()
    {
        return unlink($this->file);
    }
}
