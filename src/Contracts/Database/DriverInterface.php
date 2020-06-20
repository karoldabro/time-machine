<?php

namespace Kdabrow\TimeMachine\Contracts\Database;

use Kdabrow\TimeMachine\Database\Field;

interface DriverInterface
{
    /**
     * Return all fields for values should be updated
     *
     * @return Field[]
     */
    public function findUpdatableColumns(): array;
}
