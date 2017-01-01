<?php

namespace Igorwanbarros\BaseLaravel\Models;

class BaseModelPivot extends BaseModel
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
