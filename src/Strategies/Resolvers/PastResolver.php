<?php

namespace Kdabrow\TimeMachine\Strategies\Resolvers;

use DateTime;
use Kdabrow\TimeMachine\Contracts\TimeResolverInterface;

class PastResolver extends AbstractResolver implements TimeResolverInterface
{
    public function query($columnValue, string $columnName)
    {
        if ($columnValue instanceof DateTime) {
            $dateTime = $columnValue;
        } else if (\is_int($columnValue)) {
            $dateTime = (new DateTime())->setTimestamp($columnValue);
        } else {
            $dateTime = new DateTime($columnValue);
        }
        return $dateTime->sub($this->dateChooser->getInterval())->format("Y-m-d H:i:s");
    }
}
