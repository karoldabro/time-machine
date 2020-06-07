<?php

namespace Kdabrow\TimeMachine;

use DateTime;
use DateInterval;
use DateTimeImmutable;
use Kdabrow\TimeMachine\Contracts\DateChooserInterface;

class DateChooser implements DateChooserInterface
{
    private $dateInterval;

    private $dateTime;

    /**
     * Number of seconds
     *
     * @var int
     */
    private $timestamp;

    public function byInterval(DateInterval $dateInterval)
    {
        $this->dateInterval = $dateInterval;
        $this->timestamp = $this->dateIntervalToSeconds($dateInterval);
    }

    public function to(DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
        $this->timestamp = $dateTime->getTimestamp();
    }

    public function byTimestamp(int $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    private function dateIntervalToSeconds(DateInterval $dateInterval): int
    {
        $reference = new DateTimeImmutable;
        $endTime = $reference->add($dateInterval);

        return $endTime->getTimestamp() - $reference->getTimestamp();
    }

    /**
     * @inheritDoc
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
