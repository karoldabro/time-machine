<?php

namespace Kdabrow\TimeMachine\Tests;

use Kdabrow\TimeMachine\Providers\TimeMachineProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            TimeMachineProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }
}