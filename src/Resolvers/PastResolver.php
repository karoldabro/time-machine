<?php

namespace Kdabrow\TimeMachine\Resolvers;

use Illuminate\Support\Facades\Config;
use Kdabrow\TimeMachine\Contracts\TimeResolverInterface;

class PastResolver extends AbstractResolver implements TimeResolverInterface
{
    public function query($columnValue, string $columnName, string $columnType)
    {
        return $this
            ->resolveDateTime($columnValue, $columnType)
            ->sub($this->dateChooser->getInterval())
            ->format(Config::get('time-machine.format'));
    }
}
