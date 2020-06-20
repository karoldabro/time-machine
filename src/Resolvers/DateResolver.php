<?php

namespace Kdabrow\TimeMachine\Resolvers;

use Illuminate\Support\Facades\Config;
use Kdabrow\TimeMachine\Contracts\TimeResolverInterface;

class DateResolver extends AbstractResolver implements TimeResolverInterface
{
    public function query($columnValue, string $columnName, string $columnType)
    {
        return date(Config::get('time-machine.format'), $this->chooser->getTimestamp());
    }
}
