<?php

namespace Kdabrow\TimeMachine\Strategies\Resolvers;

use Illuminate\Support\Facades\DB;
use Kdabrow\TimeMachine\Contracts\TimeResolverInterface;

class FutureResolver extends AbstractResolver implements TimeResolverInterface
{
    public function query(string $columnName)
    {
        return DB::raw('DATE_ADD(`' . $columnName . '`, INTERVAL ' . $this->dateChooser->getTimestamp() . ' SECOND)');
    }
}
