<?php

namespace Kdabrow\TimeMachine\Database;

use Illuminate\Support\Facades\DB;
use Kdabrow\TimeMachine\Contracts\DatabaseTableInterface;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;
use Kdabrow\TimeMachine\TimeTraveller;

class Table
{
    /**
     * @var DatabaseTableInterface
     */
    private $databaseTable;

    public function __construct(DatabaseTableInterface $databaseTable)
    {
        $this->databaseTable = $databaseTable;
    }

    /**
     * @param TimeTraveller $timeTraveller
     * @return array<string, Column>
     * @throws TimeMachineException
     */
    public function columnsToUpdate(TimeTraveller $timeTraveller): array
    {
        $columns = $this->databaseTable->selectUpdatableFields($timeTraveller->getModel());

        foreach ($timeTraveller->getColumns() as $columnName => $column) {
            if (isset($columns[$columnName])) {
                $columns[$columnName]->merge($column);
            } else {
                $columns[$columnName] = $column;
            }
        }

        foreach ($columns as $columnName => $column) {
            if (isset($timeTraveller->getExcluded()[$columnName])) {
                unset($columns[$columnName]);
            }
        }

        return $columns;
    }

    /**
     * TODO: refactor as driver
     *
     * @return array
     */
    private function getAllDateFields()
    {
        $model = app($this->timeTraveller->getModel());
        $fields = DB::select("DESCRIBE " . $model->getTable());

        if (empty($fields)) {
            throw new TimeMachineException("Not found any fields in table: " . $model->getTable() . " or driver is not supported");
        }

        $columnNames = [];
        foreach ($fields as $field) {
            if (!in_array($field->Type, ['date', 'datetime', 'timestamp'])) {
                continue;
            }

            $columnNames[$field->Field] = null;
        }

        return $columnNames;
    }
}
