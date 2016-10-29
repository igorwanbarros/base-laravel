<?php

namespace Igorwanbarros\BaseLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    public function saveOrUpdate(array $data = [])
    {
        if (isset($data['id']) && $data['id'] > 0) {
            return $this->find($data['id'])->fill($data)->update();
        }

        return $this->create($data);
    }
}