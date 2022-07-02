<?php

namespace Kdabrow\TimeMachine\Contracts;

use Illuminate\Database\Eloquent\Model;
use Kdabrow\TimeMachine\Database\Column;

interface DatabaseTableInterface
{
    /**
     * Returns database table fields that can be updated
     *
     * @param Model $model
     * @return array<string, Column> Key is column name, value is Column object
     */
    public function selectUpdatableFields(Model $model): array;
}