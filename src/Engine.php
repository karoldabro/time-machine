<?php

namespace Kdabrow\TimeMachine;

use Illuminate\Database\Eloquent\Model;
use Kdabrow\TimeMachine\Contracts\SelectorInterface;
use Kdabrow\TimeMachine\Contracts\TimeResolverInterface;
use Kdabrow\TimeMachine\Database\Column;
use Kdabrow\TimeMachine\Database\Table;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;
use Throwable;

class Engine
{
    /**
     * @var TimeTraveller[]
     */
    private $timeTravellers;
    /**
     * @var TimeResolverInterface
     */
    private $resolver;
    /**
     * @var SelectorInterface
     */
    private $selector;
    /**
     * @var Table
     */
    private $table;
    /**
     * @var Result
     */
    private $result;

    public function __construct(
        array $timeTravellers,
        TimeResolverInterface $resolver,
        SelectorInterface $selector,
        Table $table,
        Result $result
    ) {
        $this->timeTravellers = $timeTravellers;
        $this->resolver = $resolver;
        $this->selector = $selector;
        $this->table = $table;
        $this->result = $result;
    }

    /**
     * @return Result
     * @throws TimeMachineException
     */
    public function start(): Result
    {
        foreach ($this->timeTravellers as $timeTraveller) {
            $columnsToUpdate = $this->table->columnsToUpdate($timeTraveller);

            $columnsToSelect = array_merge($timeTraveller->getKeys(), array_keys($columnsToUpdate));

            $results = $this->selector->getRecords($timeTraveller, $columnsToSelect, $this->result);

            if ($results->isEmpty()) {
                continue;
            }

            foreach ($results as $result) {
                try {
                    foreach ($columnsToUpdate as $columnName => $column) {
                        $this->updateValue($result, $columnName, $column);
                    }

                    $result->save();

                    $this->result->addSuccessful($result);
                } catch (Throwable $exception) {
                    $this->result->addFailed($result);
                }
            }
        }

        return $this->result;
    }

    private function updateValue(Model $result, string $columnName, Column $column)
    {
        if (is_callable($column->getCallback())) {
            // When user wants to change value based on customer callback given to TimeTraveller object
            $result->{$columnName} = call_user_func($column->getCallback(),$result->{$columnName}, $column, $this->result, $result);
        } else {
            if (!empty($result->{$columnName})) {
                $result->{$columnName} = $this->resolver->query($result->{$columnName}, $column);
            }
        }
    }
}