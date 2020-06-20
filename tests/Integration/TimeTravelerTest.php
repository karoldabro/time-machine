<?php

namespace Kdabrow\TimeMachine\Tests\Integration;

use DateTime;
use DateInterval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Kdabrow\TimeMachine\TimeMachine;
use Kdabrow\TimeMachine\TimeTraveler;
use Kdabrow\TimeMachine\Choosers\PeriodChooser;
use Kdabrow\TimeMachine\Tests\Integration\Database\ConnectedModel;
use Kdabrow\TimeMachine\Tests\Integration\Database\Model;

class TimeTravelerTest extends TestCase
{
    public function test_traveler_with_conditions_as_array()
    {
        $notToChange = factory(Model::class, 3)->create(['bool' => true]);
        $toChange = factory(Model::class, 3)->create(['bool' => false]);

        $interval = new DateInterval("P2M");

        $date = new PeriodChooser();
        $date->byInterval($interval);

        $timeMachine = new TimeMachine();
        $timeMachine
            ->take(new TimeTraveler(Model::class, [
                ['bool', '=', false]
            ]))
            ->toPast($date);

        foreach ($toChange as $model) {
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

    public function test_traveler_with_conditions_as_callback()
    {
        $notToChange = factory(Model::class, 3)->create(['bool' => true]);
        $toChange = factory(Model::class, 3)->create(['bool' => false]);

        $interval = new DateInterval("P1M");

        $date = new PeriodChooser();
        $date->byInterval($interval);

        $timeMachine = new TimeMachine();
        $timeMachine
            ->take(new TimeTraveler(Model::class, function (Builder $builder, $allUploadedTravelers) {
                return $builder->where('bool', '=', false);
            }))
            ->toPast($date);

        foreach ($toChange as $model) {
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

    public function test_if_traveler_has_previous_results()
    {
        $notToChange = factory(Model::class, 3)->create(['bool' => true]);
        $notToChangeConnected = factory(ConnectedModel::class, 3)->create(['bool' => true, 'model_id' => $notToChange->first()->id]);
        $toChange = factory(Model::class, 3)->create(['bool' => false]);
        $toChangeConnected = factory(ConnectedModel::class, 3)->create(['bool' => true, 'model_id' => $toChange->first()->id]);

        $interval = new DateInterval("P1M");

        $date = new PeriodChooser();
        $date->byInterval($interval);

        $modelTraveler = new TimeTraveler(Model::class, function (Builder $builder) {
            return $builder->where('bool', '=', false);
        });

        $timeMachine = new TimeMachine();
        $timeMachine
            ->take($modelTraveler)
            ->take(new TimeTraveler(ConnectedModel::class, function (Builder $builder, $allUploadedTravelers) {
                $modelIds = Arr::pluck($allUploadedTravelers[Model::class], 'id');
                return $builder->whereIn('model_id', $modelIds);
            }))
            ->toPast($date);

        foreach ($toChange as $model) {
            $this->compareModel(
                $model->id,
                (new DateTime($model->date))->sub($interval)->format("Y-m-d"),
                $model->datetime->sub($interval)->format("Y-m-d H:i:s"),
                $model->timestamp->sub($interval)->format("Y-m-d H:i:s"),
                (new DateTime($model->email_verified_at))->sub($interval)->format("Y-m-d H:i:s"),
                (new DateTime($model->created_at))->sub($interval)->format("Y-m-d H:i:s"),
            );

            foreach ($toChangeConnected as $connectedModel) {
                $this->compareConnectedModelByModelId(
                    $connectedModel->model_id,
                    (new DateTime($connectedModel->date))->sub($interval)->format("Y-m-d"),
                    $connectedModel->datetime->sub($interval)->format("Y-m-d H:i:s"),
                    $connectedModel->timestamp->sub($interval)->format("Y-m-d H:i:s"),
                    (new DateTime($connectedModel->created_at))->sub($interval)->format("Y-m-d H:i:s"),
                );
            }
        }
    }
}
