<?php

namespace Kdabrow\TimeMachine\Contracts;

use Kdabrow\TimeMachine\Database\Column;

interface TimeResolverInterface
{
    /**
     * Query that determine how to update filed
     *
     * @param mixed $value
     * @param Column $column
     *
     * @return mixed
     */
    public function query($value, Column $column);
}
