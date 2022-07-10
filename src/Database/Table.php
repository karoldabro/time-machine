<?php

namespace Kdabrow\TimeMachine\Database;

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
        $columns = $this->databaseTable->selectUpdatableFields($timeTraveller);

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
}
