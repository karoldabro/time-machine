<?php

namespace Kdabrow\TimeMachine\Contracts;

interface TimeResolverInterface
{
    /**
     * Query that determine how to update filed
     *
     * @param string $columnName
     *
     * @return mixed
     */
    public function query($columnValue, string $columnName);
}
