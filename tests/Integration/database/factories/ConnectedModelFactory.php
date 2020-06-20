<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Kdabrow\TimeMachine\Tests\Integration\Database\ConnectedModel;
use Kdabrow\TimeMachine\Tests\Integration\Database\Model;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(ConnectedModel::class, function (Faker $faker) {
    return [
        'model_id' => function () {
            return factory(Model::class)->create()->id;
        },
        'name' => $faker->name,
        'bool' => $faker->boolean(),
        'date' => $faker->date(),
        'datetime' => $faker->dateTime(),
        'timestamp' => $faker->dateTime(),
    ];
});
