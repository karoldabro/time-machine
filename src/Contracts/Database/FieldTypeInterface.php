<?php

namespace Kdabrow\TimeMachine\Contracts\Database;

use DateTime;

interface FieldTypeInterface
{
    public function toDateTime($value): DateTime;
}
