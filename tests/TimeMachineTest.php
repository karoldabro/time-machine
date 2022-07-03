<?php

namespace Kdabrow\TimeMachine\Tests;

use Kdabrow\TimeMachine\Contracts\DatabaseTableInterface;
use Kdabrow\TimeMachine\Contracts\DateChooserInterface;
use Kdabrow\TimeMachine\Contracts\SelectorInterface;
use Kdabrow\TimeMachine\Engine;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;
use Kdabrow\TimeMachine\TimeMachine;
use Kdabrow\TimeMachine\TimeTraveller;
use Mockery\MockInterface;

class TimeMachineTest extends TestCase
{
    /** @test */
    public function it_throws_exception_when_not_found_any_travellers()
    {
        $this->expectException(TimeMachineException::class);

        $dateChooserMock = \Mockery::mock(DateChooserInterface::class);

        $timeMachine = new TimeMachine();

        $timeMachine->toThePast($dateChooserMock);
    }

    /** @test */
    public function it_return_engine_in_any_time_travel_option()
    {
        $timeTravellerMock = \Mockery::mock(TimeTraveller::class);
        $dateChooserMock = \Mockery::mock(DateChooserInterface::class);

        $this->mock(DatabaseTableInterface::class, function(MockInterface $mock) {});
        $this->mock(SelectorInterface::class, function(MockInterface $mock) {});

        $this->assertInstanceOf(
            Engine::class,
            (new TimeMachine())
                ->take($timeTravellerMock)
                ->toThePast($dateChooserMock)
        );

        $this->assertInstanceOf(
            Engine::class,
            (new TimeMachine())
                ->take($timeTravellerMock)
                ->toTheFuture($dateChooserMock)
        );

        $this->assertInstanceOf(
            Engine::class,
            (new TimeMachine())
                ->take($timeTravellerMock)
                ->toTheDate($dateChooserMock)
        );
    }
}