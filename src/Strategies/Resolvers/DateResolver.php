<?php

namespace Kdabrow\TimeMachine\Strategies\Resolvers;

use Illuminate\Support\Facades\Config;
use Kdabrow\TimeMachine\Contracts\TimeResolverInterface;

class DateResolver extends AbstractResolver implements TimeResolverInterface
{
    public function query(string $columnName)
    {
        return date(Config::get('time-machine.date-format'), $this->dateChooser->getTimestamp());
    }
}
