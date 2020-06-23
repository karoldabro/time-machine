<?php

namespace Kdabrow\TimeMachine\Tests\Unit\Choosers;

use Kdabrow\TimeMachine\Choosers\PeriodChooser;
use Kdabrow\TimeMachine\Tests\Unit\TestCase;

class PeriodChooserTest extends TestCase
{
    public function test_if_week_setter_return_correct_interval()
    {
        $periodChooser = new PeriodChooser;
        $periodChooser->weeks(2);

        $this->assertEquals('14', $periodChooser->getInterval()->format('%d'));
    }

    public function test_if_months_return_correct_interval()
    {
        $periodChooser = new PeriodChooser;
        $periodChooser->months(3);

        $this->assertEquals('3', $periodChooser->getInterval()->format('%m'));
    }

    public function test_if_years_return_correct_interval()
    {
        $periodChooser = new PeriodChooser;
        $periodChooser->years(3);

        $this->assertEquals('3', $periodChooser->getInterval()->format('%y'));
    }

    public function test_if_days_return_correct_interval()
    {
        $periodChooser = new PeriodChooser;
        $periodChooser->days(3);

        $this->assertEquals('3', $periodChooser->getInterval()->format('%d'));
    }

    public function test_if_hours_return_correct_interval()
    {
        $periodChooser = new PeriodChooser;
        $periodChooser->hours(3);

        $this->assertEquals('3', $periodChooser->getInterval()->format('%h'));
    }

    public function test_if_minutes_return_correct_interval()
    {
        $periodChooser = new PeriodChooser;
        $periodChooser->minutes(3);

        $this->assertEquals('3', $periodChooser->getInterval()->format('%i'));
    }

    public function test_if_seconds_return_correct_interval()
    {
        $periodChooser = new PeriodChooser;
        $periodChooser->seconds(3);

        $this->assertEquals('3', $periodChooser->getInterval()->format('%s'));
    }
}
