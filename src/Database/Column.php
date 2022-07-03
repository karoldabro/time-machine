<?php

namespace Kdabrow\TimeMachine\Database;

use Closure;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;

class Column
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string|null
     */
    private $type = null;
    /**
     * @var Closure|null
     */
    private $callback = null;
    /**
     * @var mixed
     */
    private $value = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function merge(Column $column): self
    {
        if ($this->name !== $column->name) {
            throw new TimeMachineException("Can not merge different columns");
        }

        if ($this->type !== $column->type) {
            $this->type = $column->type;
        }

        if ($this->value != $column->value) {
            $this->value = $column->value;
        }

        if (is_callable($column->callback)) {
            $this->callback = $column->callback;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Column
     */
    public function setType(string $type): Column
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Closure|null
     */
    public function getCallback(): ?Closure
    {
        return $this->callback;
    }

    /**
     * @param Closure $callback
     * @return Column
     */
    public function setCallback(Closure $callback): Column
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return Column
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}