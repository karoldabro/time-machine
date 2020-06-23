<?php

namespace Kdabrow\TimeMachine\Tests\Unit;

use Mockery;
use Kdabrow\TimeMachine\Providers\TimeMachineProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

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
}
