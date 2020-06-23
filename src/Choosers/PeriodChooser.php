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
        $this->dateInterval = new DateInterval('PT' . $seconds . 'S');
    }

    public function minutes(int $minutes = 1)
    {
        $this->dateInterval = new DateInterval('PT' . $minutes . 'M');
    }

    public function hours(int $hours = 1)
    {
        $this->dateInterval = new DateInterval('PT' . $hours . 'H');
    }

    public function days(int $days = 1)
    {
        $this->dateInterval = new DateInterval('P' . $days . 'D');
    }

    public function weeks(int $weeks = 1)
    {
        $this->dateInterval = new DateInterval('P' . ($weeks * 7) . 'D');
    }

    public function months(int $months = 1)
    {
        $this->dateInterval = new DateInterval('P' . $months . 'M');
    }

    public function years(int $years = 1)
    {
        $this->dateInterval = new DateInterval('P' . $years . 'Y');
    }

    public function getInterval(): DateInterval
    {
        return $this->dateInterval;
    }
}
