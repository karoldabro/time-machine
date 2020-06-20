<?php

namespace Kdabrow\TimeMachine\Resolvers;

use Kdabrow\TimeMachine\DateChooser;
use Kdabrow\TimeMachine\TimeMachine;
use Kdabrow\TimeMachine\TimeTraveler;
use Illuminate\Database\Eloquent\Model;
use Kdabrow\TimeMachine\Contracts\Database\DriverInterface;
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
        $allUpdatedTimeTravelers = [];
        foreach ($this->timeMachine->getTravelers() as $traveler) {

            $query = $this->queryForItemsFromDb($traveler, $allUpdatedTimeTravelers);

            if ($query === false) {
                continue;
            }

            $results = $query->get();

            if ($results->isEmpty()) {
                continue;
            }

            $updatetableColumns = $this->resolveDriver($traveler)->findUpdatableColumns();

            foreach ($results as $model) {
                foreach ($updatetableColumns as $field) {
                    if (is_callable($field->getValue())) {
                        $model->{$field->getName()} = call_user_func($$field->getValue(), $model->{$field->getName()}, $field->getName(), $allUpdatedTimeTravelers, $model);
                    } else {
                        if (!empty($model->{$field->getName()})) {
                            $model->{$field->getName()} = $this->query($model->{$field->getName()}, $field->getName());
                        }
                    }
                }

                $model->save();

                $traveler->addUpdated($model);

                $allUpdatedTimeTravelers[get_class($model)][] = $model->toArray();
            }
        }

        return true;
    }

    private function queryForItemsFromDb(TimeTraveler $traveler, &$allUpdatedTimeTravelers)
    {
        $model = $this->resolveModel($traveler->getModel());

        $query = $model->query();

        if (!is_null($traveler->getConditions())) {
            if (is_callable($traveler->getConditions())) {
                $query = call_user_func($traveler->getConditions(), $query, $allUpdatedTimeTravelers);
            }

            if (is_null($query)) {
                return false;
            }

            if (is_array($traveler->getConditions())) {
                $query->where($traveler->getConditions());
            }
        }

        return $query;
    }

    private function resolveModel($modelName): Model
    {
        return app($modelName);
    }

    private function resolveDriver(TimeTraveler $timeTraveler): DriverInterface
    {
        return new MysqlDriver($timeTraveler);
    }

    public abstract function query($columnValue, string $columnName);
}
