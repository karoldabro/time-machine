<?php

namespace Kdabrow\TimeMachine;

use DateInterval;

class IntervalTranslator
{
    /**
     * @var DateInterval
     */
    private $dateInterval;

    public function __construct(DateInterval $dateInterval)
    {
        $this->dateInterval = $dateInterval;
    }

    public function translate()
    {
        $string = '';

        if ($this->dateInterval->y != 0) {
            $string .=  $this->addValue($string) . $this->dateInterval->y . ' YEAR';
        }

        if ($this->dateInterval->m != 0) {
            $string .=  $this->addValue($string) . $this->dateInterval->m . ' MONTH';
        }

        if ($this->dateInterval->d != 0) {
            $string .=  $this->addValue($string) . $this->dateInterval->d . ' DAY';
        }

        if ($this->dateInterval->i != 0) {
            $string .=  $this->addValue($string) . $this->dateInterval->i . ' MINUTE';
        }

        if ($this->dateInterval->s != 0) {
            $string .= $this->addValue($string) . $this->dateInterval->s . ' SECOND';
        }

        return $string . '';
    }

    private function addValue($string)
    {
        return (!empty($string) ? ' + ' : '');
    }
}
