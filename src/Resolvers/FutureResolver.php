<?php

namespace Kdabrow\TimeMachine\Resolvers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Kdabrow\TimeMachine\Contracts\TimeResolverInterface;

class FutureResolver extends AbstractResolver implements TimeResolverInterface
{
    public function query($columnValue, string $columnName, string $columnType)
    {
        return $this
            ->resolveDateTime($columnValue, $columnType)
            ->add($this->dateChooser->getInterval())
            ->format(Config::get('time-machine.format'));
    }
}
