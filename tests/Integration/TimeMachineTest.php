<?php

namespace Kdabrow\TimeMachine\Tests\Integration;

use DateTime;
use DateInterval;
use Kdabrow\TimeMachine\TimeMachine;
use Kdabrow\TimeMachine\TimeTraveler;
use Kdabrow\TimeMachine\Choosers\PeriodChooser;
use Kdabrow\TimeMachine\Tests\Integration\Database\Model;

class TimeMachineTest extends TestCase
{
    public function test_if_only_dates_are_changed()
    {
        $model = factory(Model::class)->create();

        $interval = new DateInterval("P1M");

        $date = new PeriodChooser();
        $date->byInterval($interval);

        $timeMachine = new TimeMachine();
        $timeMachine
            ->take(new TimeTraveler(Model::class))
            ->toPast($date);

        $this->compareModel(
            $model->id,
            (new DateTime($model->date))->sub($interval)->format("Y-m-d"),
            $model->datetime->sub($interval)->format("Y-m-d H:i:s"),
            $model->timestamp->sub($interval)->format("Y-m-d H:i:s"),
            (new DateTime($model->email_verified_at))->sub($interval)->format("Y-m-d H:i:s"),
            (new DateTime($model->created_at))->sub($interval)->format("Y-m-d H:i:s"),
        );
    }
}
