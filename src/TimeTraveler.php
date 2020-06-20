<?php

namespace Kdabrow\TimeMachine;

use Closure;
use Illuminate\Database\Eloquent\Model;

class TimeTraveler
{
    /**
     * Model name
     *
     * @var string
     */
    private $model;

    /**
     * Conditions by to select change rows
     *
     * @var array|Closure|null
     */
    private $conditions;

    /**
     * Additional columns to change
     *
     * @var array
     */
    private $columns;

    /**
     * Columns that shouldn't be changed
     *
     * @var array
     */
    private $excluded;

    /**
     * Time traveler represents model or table in database
     *
     * @param string|Model $model Model name
     * @param array|Closure|null $conditions Conditions by to select change rows
     */
    public function __construct($model, $conditions = null)
    {
        if ($model instanceof Model) {
            $this->model = class_basename($model);
        } else {
            $this->model = $model;
        }
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
        $this->columns[$column] = $how;
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
        $this->excluded[] = $column;

        return $this;
    }

    /**
     * Get model name
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get conditions by to select change rows
     *
     * @return array|Closure|null
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Get additional columns to change
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get columns that shouldn't be changed
     *
     * @return  array
     */
    public function getExcluded()
    {
        return $this->excluded;
    }
}
