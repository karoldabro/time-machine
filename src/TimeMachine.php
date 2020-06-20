<?php

namespace Kdabrow\TimeMachine;

use Kdabrow\TimeMachine\DateChooser;
use Kdabrow\TimeMachine\TimeTraveler;
use Kdabrow\TimeMachine\Resolvers\DateResolver;
use Kdabrow\TimeMachine\Resolvers\PastResolver;
use Kdabrow\TimeMachine\Resolvers\FutureResolver;

class TimeMachine
{
    /**
     * All time travelers
     *
     * @var TimeTraveler[]
     */
    private $travelers = [];

    public function take(TimeTraveler $timeTraveler): self
    {
        $this->travelers[] = $timeTraveler;

        return $this;
    }

    public function toPast(PeriodChooser $chooser): bool
    {
        return app(PastResolver::class, [
            'timeMachine' => $this,
            'chooser' => $chooser,
        ])->resolve();
    }

    public function toFuture(PeriodChooser $chooser): bool
    {
        return app(FutureResolver::class, [
            'timeMachine' => $this,
            'chooser' => $chooser,
        ])->resolve();
    }

    public function toDate(DateChooser $chooser): bool
    {
        return app(DateResolver::class, [
            'timeMachine' => $this,
            'chooser' => $chooser,
        ])->resolve();
    }

    /**
     * Get all time travelers
     *
     * @return TimeTraveler[]
     */
    public function getTravelers()
    {
        return $this->travelers;
    }
}
