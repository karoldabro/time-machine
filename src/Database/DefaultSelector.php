<?php

namespace Kdabrow\TimeMachine\Database;

use Illuminate\Support\Collection;
use Kdabrow\TimeMachine\Contracts\SelectorInterface;
use Kdabrow\TimeMachine\Result;
use Kdabrow\TimeMachine\TimeTraveller;

class DefaultSelector implements SelectorInterface
{
    public function getRecords(TimeTraveller $timeTraveller, array $columns, Result $result): Collection
    {
        $query = $timeTraveller
            ->getModel()
            ->query()
            ->select($columns);

        if (!is_null($timeTraveller->getConditions()) && is_callable($timeTraveller->getConditions())) {
            $query = call_user_func($timeTraveller->getConditions(), $query, $result);

            if (is_null($query)) {
                return new Collection([]);
            }
        }

        return $query->get()->toBase();
    }
}