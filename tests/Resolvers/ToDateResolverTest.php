<?php

namespace Kdabrow\TimeMachine\Tests\Resolvers;

use DateTime;
use Kdabrow\TimeMachine\Database\Column;
use Kdabrow\TimeMachine\DateChooser;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;
use Kdabrow\TimeMachine\Resolvers\FutureResolver;
use Kdabrow\TimeMachine\Resolvers\ToDateResolver;
use Kdabrow\TimeMachine\Tests\TestCase;

class ToDateResolverTest extends TestCase
{
    /** @test */
    public function when_value_is_datetime_return_same_value()
    {
        $dateChooser = new DateChooser(new DateTime("2020-06-17 12:12:12"));

        $resolver = new ToDateResolver($dateChooser);

        $this->assertEquals(
            "2020-06-17 12:12:12",
            $resolver->query(new DateTime("2020-06-16 12:12:12"), new Column("test"))->format("Y-m-d H:i:s")
        );
    }
}