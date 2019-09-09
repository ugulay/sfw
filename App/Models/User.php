<?php

namespace App\Models;

use Kernel\Model;

class User extends Model
{

    const TABLE = 'users';
    const PK = 'id';

    public function getUsers($where = [], $start = 0, $limit = 10)
    {
        $where['LIMIT'] = [$start, $limit];
        $data = $this->DB()->select(self::TABLE, "*", $where);
        return $data;
    }

    public function checkLogin($where = [])
    {
        return $this->DB()->has(self::TABLE, $where);

    }

    public function getUser($where = [])
    {
        $data = $this->DB()->get(self::TABLE, "*", $where);
        return $data;
    }


}
