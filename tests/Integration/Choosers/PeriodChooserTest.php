<?php

namespace Kdabrow\TimeMachine\Tests\Integration;

use DateInterval;
use DateTime;
use Kdabrow\TimeMachine\Choosers\PeriodChooser;
use Kdabrow\TimeMachine\TimeMachine;
use Kdabrow\TimeMachine\TimeTraveler;
use Kdabrow\TimeMachine\Tests\Integration\TestCase;
use Kdabrow\TimeMachine\Tests\Integration\Database\Model;

class PeriodChooserTest extends TestCase
{
    public function test_if_data_is_change_to_specified_date()
    {
        $model = factory(Model::class)->create();

        $interval = new DateInterval('PT30M');

        $date = new PeriodChooser();
        $date->minutes(30);

        $timeMachine = new TimeMachine();
        $timeMachine
            ->take(new TimeTraveler(Model::class))
            ->toFuture($date);

        $this->compareModel(
            $model->id,
            (new DateTime($model->date))->add($interval)->format("Y-m-d"),
            $model->datetime->add($interval)->format("Y-m-d H:i:s"),
            $model->timestamp->add($interval)->format("Y-m-d H:i:s"),
            (new DateTime($model->email_verified_at))->add($interval)->format("Y-m-d H:i:s"),
            (new DateTime($model->created_at))->add($interval)->format("Y-m-d H:i:s"),
        );
    }
}
