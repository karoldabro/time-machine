<?php

namespace Kdabrow\TimeMachine\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Kdabrow\TimeMachine\Result;
use Kdabrow\TimeMachine\TimeTraveller;

interface SelectorInterface
{
    /**
     * @param TimeTraveller $timeTraveller
     * @param string[] $columns Columns that should be affected by the change
     * @param Result $result Previously affected rows for other time traveller
     * @return Collection<int, Model>
     */
    public function getRecords(TimeTraveller $timeTraveller, array $columns, Result $result): Collection;
}