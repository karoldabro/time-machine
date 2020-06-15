<?php

namespace Kdabrow\TimeMachine\Strategies\Resolvers;

use DateInterval;
use Kdabrow\TimeMachine\DateChooser;
use Kdabrow\TimeMachine\Finders\Column;
use Kdabrow\TimeMachine\TimeMachine;

abstract class AbstractResolver
{
    /**
     * @var DateChooser
     */
    protected $dateChooser;

    /**
     * @var TimeMachine
     */
    private $timeMachine;

    public function __construct(TimeMachine $timeMachine, DateChooser $dateChooser)
    {
        $this->timeMachine = $timeMachine;
        $this->dateChooser = $dateChooser;
    }

    public function resolve(): bool
    {
        $updated = [];
        foreach ($this->timeMachine->getTravelers() as $traveller) {

            $model = app($traveller->getModel());

            $query = $model->query();

            if (!is_null($traveller->getConditions())) {
                if (is_callable($traveller->getConditions())) {
                    $query = call_user_func($traveller->getConditions(), $query, $updated);
                }

                if (is_array($traveller->getConditions())) {
                    $query->where($traveller->getConditions());
                }
            }

            $results = $query->get();

            foreach ($results as $result) {
                $toUpdate = [];
                foreach ((new Column($traveller))->toUpdate() as $columnName => $columnValue) {
                    if (is_callable($columnValue)) {
                        $toUpdate[$columnName] = call_user_func($columnValue, $result->{$columnName}, $columnName, $updated, $toUpdate);
                    } else {
                        $toUpdate[$columnName] = $this->query($result->{$columnName}, $columnName);
                    }
                }
                $updated[] = $query->update($toUpdate);
            }
        }

        return true;
    }

    public abstract function query($columnValue, string $columnName);
}
