<?php

namespace Kdabrow\TimeMachine\Providers;

use Illuminate\Support\ServiceProvider;
use Kdabrow\TimeMachine\Contracts\SelectorInterface;
use Kdabrow\TimeMachine\Database\DefaultSelector;

class TimeMachineProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SelectorInterface::class, DefaultSelector::class);
    }
}