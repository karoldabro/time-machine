<?php

namespace Kdabrow\TimeMachine\Tests\Integration;

use DateTime;
use Kdabrow\TimeMachine\Choosers\DateChooser;
use Kdabrow\TimeMachine\TimeMachine;
use Kdabrow\TimeMachine\TimeTraveler;
use Kdabrow\TimeMachine\Tests\Integration\TestCase;
use Kdabrow\TimeMachine\Tests\Integration\Database\Model;

class DateChooserTest extends TestCase
{
    public function test_if_data_is_change_to_specified_date()
    {
        $model = factory(Model::class)->create();

        $dateTime = new DateTime("2005-04-02 21:37:00");

        $date = new DateChooser();
        $date->to($dateTime);

        $timeMachine = new TimeMachine();
        $timeMachine
            ->take(new TimeTraveler(Model::class))
            ->toDate($date);

        $this->compareModel(
            $model->id,
            $dateTime->format("Y-m-d"),
            $dateTime->format("Y-m-d H:i:s"),
            $dateTime->format("Y-m-d H:i:s"),
            $dateTime->format("Y-m-d H:i:s"),
            $dateTime->format("Y-m-d H:i:s"),
        );
    }
}
