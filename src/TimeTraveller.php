<?php

namespace Kdabrow\TimeMachine;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Kdabrow\TimeMachine\Database\Column;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;

class TimeTraveller
{
    /**
     * Model
     *
     * @var Model
     */
    private $model;

    /**
     * Conditions by to select change rows
     *
     * @var Closure|null
     */
    private $conditions;

    /**
     * Additional columns to change
     *
     * @var array<string, Column>
     */
    private $columns = [];

    /**
     * Columns that shouldn't be changed
     *
     * @var Column[]
     */
    private $excluded = [];

    /**
     * Primary or other set of the keys
     * @var string[]
     */
    private $keys = ['id'];

    /**
     * Time traveller represents model or table in database
     *
     * @param string|Model $model Model name
     * @param Closure<mixed, string, array<string, array<int, Model>>, Model>|null $conditions Conditions by to select change rows
     * @param string[] $keys Name of the primary key, or other set of keys
     */
    public function __construct($model, Closure $conditions = null)
    {
        $this->model = $this->makeModel($model);

        $this->keys = [$this->model->getKeyName()];

        $this->conditions = $conditions;
    }

    /**
     * Additional column to change
     *
     * @param string $column
     * @param Closure|null $how
     *
     * @return self
     */
    public function alsoChange(string $column, Closure $how = null): self
    {
        $this->columns[$column] = (new Column($column))->setCallback($how);

        return $this;
    }

    /**
     * Exclude columns from date change
     *
     * @param string $column
     *
     * @return self
     */
    public function exclude(string $column): self
    {
        $this->excluded[$column] = new Column($column);

        return $this;
    }

    /**
     * Get eloquent model
     *
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Get conditions by to select change rows
     *
     * @return Closure|null
     */
    public function getConditions(): ?Closure
    {
        return $this->conditions;
    }

    /**
     * Get additional columns to change
     *
     * @return array<string, callable>
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get columns that shouldn't be changed
     *
     * @return string[]
     */
    public function getExcluded()
    {
        return $this->excluded;
    }

    /**
     * @param string|Model $model
     * @return Model
     */
    private function makeModel($model)
    {
        if (is_string($model)) {
            $model = app($model);
        }

        if ($model instanceof Model === false) {
            throw new TimeMachineException("Only eloquent model can be a time traveller");
        }

        return $model;
    }

    /**
     * @param string[] $keys
     * @return self
     */
    public function setKeys(array $keys): self
    {
        $this->keys = $keys;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getKeys(): array
    {
        return $this->keys;
    }
}
