<?php

namespace Kdabrow\TimeMachine\Resolvers;

use DateTime;
use Kdabrow\TimeMachine\Contracts\TimeResolverInterface;
use Kdabrow\TimeMachine\Database\Column;

class ToDateResolver extends AbstractResolver implements TimeResolverInterface
{
    public function query($value, Column $column)
    {
        return (new DateTime())->setTimestamp($this->dateChooser->getTimestamp());
    }
}
