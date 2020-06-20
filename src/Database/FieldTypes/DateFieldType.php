<?php

namespace Kdabrow\TimeMachine\Database\FieldTypes;

use DateTime;
use Kdabrow\TimeMachine\Contracts\Database\FieldTypeInterface;

class DateFieldType implements FieldTypeInterface
{
    public function toDateTime($value): DateTime
    {
        return new DateTime($value);
    }
}
