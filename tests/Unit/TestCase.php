<?php

namespace Kdabrow\TimeMachine\Tests\Unit;

use Mockery;
use Kdabrow\TimeMachine\Providers\TimeMachineProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Kdabrow\TimeMachine\Tests\Integration\Database\Model;

class TestCase extends TestbenchTestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    protected function getPackageProviders($app)
    {
        return [
            TimeMachineProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->bind(Model::class, function () {
            return new Model();
        });
    }
}
