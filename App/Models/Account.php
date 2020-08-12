<?php

namespace App\Models;

use Kernel\Model;

class Account extends Model
{

    const TABLE = 'accounts';
    const PK = 'id';

    public function getAccounts($where = [], $start = 0, $limit = 10)
    {
        $where['LIMIT'] = [$start, $limit];
        $data = $this->DB()->select(self::TABLE, "*", $where);
        return $data;
    }

    public function addAccount($data = [])
    {
        $data = $this->DB()->insert(self::TABLE, $data);
        return $this->DB()->id();
    }

    public function updateAccount($data = [], $where = [])
    {
        $data = $this->DB()->update(self::TABLE, $data, $where);
        return $data->rowCount();
    }

    public function checkLogin($where = [])
    {
        return $this->DB()->count(self::TABLE, $where);
    }

    public function getAccount($where = [])
    {
        $data = $this->DB()->get(self::TABLE, "*", $where);
        return $data;
    }

}
