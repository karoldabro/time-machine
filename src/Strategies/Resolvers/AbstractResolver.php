<?php

namespace Kdabrow\TimeMachine\Strategies\Resolvers;

use Kdabrow\TimeMachine\DateChooser;
use Kdabrow\TimeMachine\TimeMachine;
use Kdabrow\TimeMachine\TimeTraveler;
use Illuminate\Database\Eloquent\Model;
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
        foreach ($this->timeMachine->getTravelers() as $traveler) {

            $results = $this->getItemsFromDb($traveler, $updated);

            foreach ($results as $result) {
                $toUpdate = [];
                foreach ((new MysqlDriver($traveler))->findUpdatableColumns() as $field) {
                    if (is_callable($field->getValue())) {
                        $toUpdate[$field->getName()] = call_user_func($$field->getValue(), $result->{$field->getName()}, $field->getName(), $updated, $toUpdate);
                    } else {
                        $toUpdate[$field->getName()] = $this->query($result->{$field->getName()}, $field->getName());
                    }
                }
                $updated[] = $result->update($toUpdate);
            }
        }

        return true;
    }

    private function getItemsFromDb(TimeTraveler $traveler, &$updated)
    {
        $model = $this->resolveModel($traveler->getModel());

        $query = $model->query();

        if (!is_null($traveler->getConditions())) {
            if (is_callable($traveler->getConditions())) {
                $query = call_user_func($traveler->getConditions(), $query, $updated);
            }

            if (is_array($traveler->getConditions())) {
                $query->where($traveler->getConditions());
            }
        }

        return $query->get();
    }

    private function resolveModel($modelName): Model
    {
        return app($modelName);
    }

    public abstract function query($columnValue, string $columnName);
}
