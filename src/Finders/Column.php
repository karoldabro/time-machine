<?php

namespace Kdabrow\TimeMachine\Finders;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;
use Kdabrow\TimeMachine\TimeTraveler;

class Column
{
    /**
     * @var TimeTraveler
     */
    private $timeTraveler;

    public function __construct(TimeTraveler $timeTraveler)
    {
        $this->timeTraveler = $timeTraveler;
    }

    public function toUpdate()
    {
        $columnNames = $this->getAllDateFields();
        $columnNames = array_merge($columnNames, $this->timeTraveler->getColumns());
        Arr::forget($columnNames, $this->timeTraveler->getExcluded());
        return $columnNames;
    }

    private function getAllDateFields()
    {
        $model = app($this->timeTraveler->getModel());
        $fields = DB::select("DESCRIBE " . $model->getTable());

        if (empty($fields)) {
            throw new TimeMachineException("Not found any fields in table: " . $model->getTable() . " or driver is not supported");
        }

        $columnNames = [];
        foreach ($fields as $field) {
            if (!in_array($field, ['date', 'datetime', 'timestamp'])) {
                continue;
            }

            $columnNames[$field['Field']] = null;
        }

        return $columnNames;
    }
}
