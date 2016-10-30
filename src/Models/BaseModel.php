<?php

namespace Igorwanbarros\BaseLaravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    protected static $informationSchema;


    public static function getSchema()
    {
        if (env('DB_CONNECTION') != 'mysql') {
            return [];
        }

        if (!isset(static::$informationSchema[get_called_class()])) {
            return static::_initInformationSchema();
        }

        return static::$informationSchema[get_called_class()];
    }


    protected static function _initInformationSchema()
    {
        $static = new static;

        $schema = DB::table('information_schema.columns')
            ->where('table_name', '=', $static->getTable())
            ->where('table_schema', '=', env('DB_DATABASE'))
            ->get();

        static::$informationSchema[get_called_class()] = $schema;

        return $schema;
    }


    public function saveOrUpdate(array $data = [])
    {
        if (isset($data['id']) && $data['id'] > 0) {
            return $this->find($data['id'])->fill($data)->update();
        }

        return $this->create($data);
    }
}