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

    public function toPast(DateChooser $dateChooser): bool
    {
        return app(PastResolver::class, [
            'timeMachine' => $this,
            'dateChooser' =>  $dateChooser,
        ])->resolve();
    }

    public function toFuture(DateChooser $dateChooser): bool
    {
        return app(FutureResolver::class, [
            'timeMachine' => $this,
            'dateChooser' =>  $dateChooser,
        ])->resolve();
    }

    public function toDate(DateChooser $dateChooser): bool
    {
        return app(DateResolver::class, [
            'timeMachine' => $this,
            'dateChooser' =>  $dateChooser,
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
