<?php

namespace Igorwanbarros\BaseLaravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    protected static $informationSchema;

    use SoftDeletes;


    public function __construct(array $attributes = [])
    {
        $schema = $this->_initInformationSchema($this->getTable());

        foreach ($schema as $column) {
            if (isset($column->COLUMN_NAME) && $column->COLUMN_NAME != $this->getDeletedAtColumn()) {
                $this->fillable[] = $column->COLUMN_NAME;
            }
        }

        parent::__construct($attributes);
    }


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


    protected static function _initInformationSchema($table = null)
    {
        if (!$table) {
            $static = new static;
            $table = $static->getTable();
        }

        $schema = DB::table('information_schema.columns')
            ->where('table_name', '=', $table)
            ->where('table_schema', '=', env('DB_DATABASE'))
            ->get();

        static::$informationSchema[get_called_class()] = $schema;

        return $schema;
    }


    /**
     * @param array $data
     *
     * @return $this
     */
    public function saveOrUpdate(array $data = [])
    {
        if (isset($data['id']) && $data['id'] > 0) {
            $model = $this->find($data['id'])->fill($data);
            $model->update();

            return $model;
        }

        return $this->create($data);
    }
}
