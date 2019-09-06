<?php

namespace Kernel;

class Slugger
{

    protected $model = null;

    public function model(Model $model)
    {
        $this->model = $model;
    }

    public function check($textOrSlug = null, $column = 'slug')
    {

        if (!$this->model) {
            throw new \Exception('Need model depency for works Slugger\check() .');
        }

        if (!$textOrSlug) {
            throw new \Exception('Slugger check input can not empty.');
        }

        $textOrSlug = self::slugify($textOrSlug);

        return $this->model->DB()->count($this->model::TABLE, '*', [$column => $textOrSlug]) ? true : false;
    }

    public static function slugify($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return $text;
    }

}
