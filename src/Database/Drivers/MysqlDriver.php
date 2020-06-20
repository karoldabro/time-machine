<?php

namespace Kdabrow\TimeMachine\Database\Drivers;

use Illuminate\Support\Facades\DB;
use Kdabrow\TimeMachine\TimeTraveler;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;
use Kdabrow\TimeMachine\Contracts\Database\DriverInterface;
use Kdabrow\TimeMachine\Database\Field;

class MysqlDriver implements DriverInterface
{
    /**
     * @var TimeTraveler
     */
    private $timeTraveler;

    private $allowedTypes = ['date', 'datetime', 'timestamp'];

    public function __construct(TimeTraveler $timeTraveler)
    {
        $this->timeTraveler = $timeTraveler;
    }

    /**
     * @inheritDoc
     */
    public function findUpdatableColumns(): array
    {
        $model = $this->getTimeTravelerModel();

        $allFieldsFromDb = $this->getAllFieldsDefinitionsFromTable($model->getTable());

        $columns = [];
        foreach ($allFieldsFromDb as $field) {
            $fieldObject = $this->createFieldObject($field);

            if (!in_array($fieldObject->getType(), $this->allowedTypes)) {
                continue;
            }

            if ($this->isAddedAsAdditionalColumn($columns, $fieldObject) == false) {
                $columns[] = $fieldObject;
            }

            $this->excludeCurrentColumnIfIsNeed($columns, $fieldObject);
        }

        return $columns;
    }

    private function excludeCurrentColumnIfIsNeed(&$columns, Field $field)
    {
        $excludedColumnKey = $this->findColumnByName($this->timeTraveler->getExcludedColumns(), $field->getName());

        if ($excludedColumnKey !== false) {
            unset($columns[key($columns)]);
        }
    }

    private function getTimeTravelerModel()
    {
        return app($this->timeTraveler->getModel());
    }

    private function getAllFieldsDefinitionsFromTable($tableName)
    {
        $allFieldsFromDb = DB::select("DESCRIBE " . $tableName);

        if (empty($allFieldsFromDb)) {
            throw new TimeMachineException("Not found any fields in table: " . $tableName . " or driver is not supported");
        }

        return $allFieldsFromDb;
    }

    private function createFieldObject($fieldFromDb): Field
    {
        $field = new Field($fieldFromDb->Field);
        $field->setType($fieldFromDb->Type);

        return $field;
    }

    private function findColumnByName($array, $name)
    {
        if (is_array($array)) {
            foreach ($array as $key => $additionalColumn) {
                if ($additionalColumn->getName() == $name) {
                    return $key;
                }
            }
        }

        return false;
    }

    private function isAddedAsAdditionalColumn(&$columns, Field $field): bool
    {
        $additionalColumnKey = $this->findColumnByName($this->timeTraveler->getAdditionalColumns(), $field->getName());

        if ($additionalColumnKey !== false) {
            $columns[] = $this->timeTraveler->getAdditionalColumns()[$additionalColumnKey];
            return true;
        }

        return false;
    }
}
