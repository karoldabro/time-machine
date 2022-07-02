<?php

namespace Kdabrow\TimeMachine\Resolvers;

use DateTime;
use Kdabrow\TimeMachine\Contracts\TimeResolverInterface;
use Kdabrow\TimeMachine\Database\Column;

class FutureResolver extends AbstractResolver implements TimeResolverInterface
{
    public function query($value, Column $column)
    {
        $dateTime = $this->parseToDateTime($value);

        $difference = $dateTime->getTimestamp() + $this->dateChooser->getTimestamp();

        return (new DateTime())->setTimestamp($difference);
    }
}
