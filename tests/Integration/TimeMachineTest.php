<?php

namespace Kdabrow\TimeMachine\Tests\Integration;

use DateInterval;
use Kdabrow\TimeMachine\DateChooser;
use Kdabrow\TimeMachine\Tests\Integration\Database\Model;
use Kdabrow\TimeMachine\TimeMachine;
use Kdabrow\TimeMachine\TimeTraveler;

class TimeMachineTest extends TestCase
{
    public function test_if_only_dates_are_changed()
    {
        $models = factory(Model::class, 10)->create();

        $date = new DateChooser();
        $date->byInterval(new DateInterval("P1M"));

        $timeMachine = new TimeMachine();
        $timeMachine
            ->take(new TimeTraveler(Model::class))
            ->toPast($date);
    }
}
