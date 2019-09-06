<?php

namespace App\Models;

use Kernel\Model;

class Post extends Model
{

    const TABLE = 'posts';
    const PK = 'id';

    public function getPosts($where = [], $start = 0, $limit = 10)
    {
        $where['LIMIT'] = [$start, $limit];
        $data = $this->DB()->select(self::TABLE, "*", $where);
        return $data;
    }

    public function getPost($where = [])
    {
        $data = $this->DB()->get(self::TABLE, "*", $where);
        return $data;
    }

}
