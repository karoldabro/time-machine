<?php

namespace Kdabrow\TimeMachine\Contracts;

interface TimeResolverInterface
{
    /**
     * Return value that will be update existing value
     *
     * @param mixed $columnValue
     * @param string $columnName
     * @param string $columnType
     *
     * @return mixed
     */
    public function query($columnValue, string $columnName, string $columnType);
}
