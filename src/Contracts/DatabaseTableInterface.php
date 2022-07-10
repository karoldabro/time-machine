<?php

namespace Kdabrow\TimeMachine\Contracts;

use Illuminate\Database\Eloquent\Model;
use Kdabrow\TimeMachine\Database\Column;
use Kdabrow\TimeMachine\TimeTraveller;

interface DatabaseTableInterface
{
    /**
     * Returns database table fields that can be updated
     *
     * @param TimeTraveller $timeTraveller
     * @return array<string, Column> Key is column name, value is Column object
     */
    public function selectUpdatableFields(TimeTraveller $timeTraveller): array;
}