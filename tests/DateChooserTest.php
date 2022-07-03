<?php

namespace Kdabrow\TimeMachine\Tests;

use Kdabrow\TimeMachine\DateChooser;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;

class DateChooserTest extends TestCase
{
    /** @test */
    public function it_returns_correct_timestamp_from_interval()
    {
        $dateChooser = new DateChooser(new \DateInterval('P3D'));

        $this->assertEquals(259200, $dateChooser->getTimestamp());
    }

    /** @test */
    public function it_returns_correct_timestamp_from_datetime()
    {
        $dateChooser = new DateChooser(new \DateTime('2020-06-15 12:12:12'));

        $this->assertEquals(1592223132, $dateChooser->getTimestamp());
    }

    /** @test */
    public function it_returns_correct_timestamp_from_integer()
    {
        $dateChooser = new DateChooser(1592212734);

        $this->assertEquals(1592212734, $dateChooser->getTimestamp());
    }

    /** @test */
    public function it_throws_exception_when_incorrect_value_is_provided()
    {
        $this->expectException(TimeMachineException::class);

        $dateChooser = new DateChooser('1592212734');
    }
}