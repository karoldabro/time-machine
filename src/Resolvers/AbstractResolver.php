<?php

namespace Kdabrow\TimeMachine\Resolvers;

use DateTime;
use DateTimeInterface;
use Kdabrow\TimeMachine\Contracts\DateChooserInterface;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;
use Throwable;

abstract class AbstractResolver
{
    /**
     * @var DateChooserInterface
     */
    protected $dateChooser;

    public function __construct(DateChooserInterface $dateChooser)
    {
        $this->dateChooser = $dateChooser;
    }

    protected function parseToDateTime($value): DateTimeInterface
    {
        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        if (is_int($value)) {
            return (new DateTime())->setTimestamp($value);
        }

        try {
            return new DateTime($value);
        } catch (Throwable $exception) {
            throw new TimeMachineException("Can not parse value: ".$value, 0, $exception);
        }
    }
}