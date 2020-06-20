<?php

namespace Kdabrow\TimeMachine\Choosers;

use DateInterval;
use Kdabrow\TimeMachine\Contracts\ChooserInterface;

class PeriodChooser implements ChooserInterface
{
    private $dateInterval;

    public function byInterval(DateInterval $dateInterval)
    {
        $this->dateInterval = $dateInterval;
    }

    public function seconds(int $seconds = 1)
    {
        $this->dateInterval = new DateInterval('P' . $seconds . 'S');
    }

    public function minutes(int $minutes = 1)
    {
        $this->dateInterval = new DateInterval('P' . $minutes . 'i');
    }

    public function hours(int $hours = 1)
    {
        $this->dateInterval = new DateInterval('P' . $hours . 'h');
    }

    public function days(int $days = 1)
    {
        $this->dateInterval = new DateInterval('P' . $days . 'd');
    }

    public function weeks(int $weeks = 1)
    {
        $this->dateInterval = new DateInterval('P' . ($weeks * 7) . 'd');
    }

    public function months(int $months = 1)
    {
        $this->dateInterval = new DateInterval('P' . $months . 'm');
    }

    public function years(int $years = 1)
    {
        $this->dateInterval = new DateInterval('P' . $years . 'y');
    }

    public function getInterval(): DateInterval
    {
        return $this->dateInterval;
    }
}
