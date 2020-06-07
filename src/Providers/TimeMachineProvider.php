<?php

namespace Kdabrow\TimeMachine\Providers;

use Illuminate\Support\ServiceProvider;

class TimeMachineProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/time-machine.php', 'time-machine');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/time-machine.php' => config_path('time-machine.php'),
        ], 'time-machine.config');
    }
}
