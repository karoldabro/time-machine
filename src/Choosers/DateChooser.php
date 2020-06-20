<?php

namespace Kdabrow\TimeMachine\Choosers;

use DateTime;
use Kdabrow\TimeMachine\Contracts\ChooserInterface;

class DateChooser implements ChooserInterface
{
    /**
     * Number of seconds
     *
     * @var int
     */
    private $timestamp;

    public function to(DateTime $dateTime)
    {
        $this->timestamp = $dateTime->getTimestamp();
    }

    public function toTimestamp(int $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
}
