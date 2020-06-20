<?php

namespace Kdabrow\TimeMachine\Database\FieldTypes;

use DateTime;
use Kdabrow\TimeMachine\Contracts\Database\FieldTypeInterface;

class TimestampFieldType implements FieldTypeInterface
{
    public function toDateTime($value): DateTime
    {
        if (is_int($value)) {
            return (new DateTime())->setTimestamp($value);
        }

        return new DateTime($value);
    }
}
