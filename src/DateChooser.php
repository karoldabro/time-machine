<?php

namespace Kdabrow\TimeMachine;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Kdabrow\TimeMachine\Contracts\DateChooserInterface;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;

class DateChooser implements DateChooserInterface
{
    /**
     * @var int
     */
    private $timestamp;

    /**
     * @param DateInterval|DateTimeInterface|int $dateSource
     * @throws TimeMachineException
     */
    public function __construct($dateSource)
    {
        $this->timestamp = $this->determineTimestamp($dateSource);
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    private function determineTimestamp($dateSource)
    {
        if ($dateSource instanceof DateInterval) {
            return $this->dateIntervalToSeconds($dateSource);
        }

        if ($dateSource instanceof DateTimeInterface) {
            return $dateSource->getTimestamp();
        }

        if (is_int($dateSource)) {
            return $dateSource;
        }

        throw new TimeMachineException("Date source should be DateInterval, DateTimeInterface or timestamp in seconds");
    }

    private function dateIntervalToSeconds(DateInterval $dateInterval): int
    {
        $reference = new DateTimeImmutable;
        $endTime = $reference->add($dateInterval);

        return $endTime->getTimestamp() - $reference->getTimestamp();
    }
}
