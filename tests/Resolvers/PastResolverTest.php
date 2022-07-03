<?php

namespace Kdabrow\TimeMachine\Tests\Resolvers;

use DateTime;
use Kdabrow\TimeMachine\Database\Column;
use Kdabrow\TimeMachine\DateChooser;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;
use Kdabrow\TimeMachine\Resolvers\PastResolver;
use Kdabrow\TimeMachine\Tests\TestCase;

class PastResolverTest extends TestCase
{
    /** @test */
    public function when_value_is_datetime_return_subtracted_value()
    {
        $dateChooser = new DateChooser(new \DateInterval('P1D'));

        $resolver = new PastResolver($dateChooser);

        $this->assertEquals(
            "2020-06-15 12:12:12",
            $resolver->query(new DateTime("2020-06-16 12:12:12"), new Column("test"))->format("Y-m-d H:i:s")
        );
    }

    /** @test */
    public function when_value_is_timestamp_return_subtracted_value()
    {
        $dateChooser = new DateChooser(new \DateInterval('P1D'));

        $resolver = new PastResolver($dateChooser);

        $this->assertEquals(
            "2020-06-15 12:12:12",
            $resolver->query(1592309532, new Column("test"))->format("Y-m-d H:i:s")
        );
    }

    /** @test */
    public function when_value_is_correct_datetime_string_return_subtracted_value()
    {
        $dateChooser = new DateChooser(new \DateInterval('P1D'));

        $resolver = new PastResolver($dateChooser);

        $this->assertEquals(
            "2020-06-15 12:12:12",
            $resolver->query("2020-06-16 12:12:12", new Column("test"))->format("Y-m-d H:i:s")
        );
    }

    /** @test */
    public function when_value_is_not_datetime_throw_exception()
    {
        $this->expectException(TimeMachineException::class);

        $dateChooser = new DateChooser(new \DateInterval('P1D'));

        $resolver = new PastResolver($dateChooser);

        $resolver->query("not-correct-time", new Column("test"));
    }
}