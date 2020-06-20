<?php

namespace Kdabrow\TimeMachine\Strategies\Resolvers;

use Kdabrow\TimeMachine\DateChooser;
use Kdabrow\TimeMachine\TimeMachine;
use Kdabrow\TimeMachine\Database\Drivers\MysqlDriver;

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
                foreach ((new MysqlDriver($traveller))->findUpdatableColumns() as $field) {
                    if (is_callable($field->getValue())) {
                        $toUpdate[$field->getName()] = call_user_func($$field->getValue(), $result->{$field->getName()}, $field->getName(), $updated, $toUpdate);
                    } else {
                        $toUpdate[$field->getName()] = $this->query($result->{$field->getName()}, $field->getName());
                    }
                }
                $updated[] = $query->update($toUpdate);
            }
        }

        return true;
    }

    public abstract function query($columnValue, string $columnName);
}
