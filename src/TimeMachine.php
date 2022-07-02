<?php

namespace Kdabrow\TimeMachine;

use Kdabrow\TimeMachine\Contracts\DateChooserInterface;
use Kdabrow\TimeMachine\Contracts\TimeResolverInterface;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;
use Kdabrow\TimeMachine\Resolvers\ToDateResolver;
use Kdabrow\TimeMachine\Resolvers\FutureResolver;
use Kdabrow\TimeMachine\Resolvers\PastResolver;

class TimeMachine
{
    /**
     * All time travellers
     *
     * @var TimeTraveller[]
     */
    private $timeTravellers = [];

    public function take(TimeTraveller $timeTraveller): self
    {
        $this->timeTravellers[] = $timeTraveller;

        return $this;
    }

    public function toThePast(DateChooserInterface $dateChooser): Engine
    {
        return $this->makeEngine(
            $this->makeResolver(PastResolver::class, $dateChooser)
        );
    }

    public function toTheFuture(DateChooserInterface $dateChooser): Engine
    {
        return $this->makeEngine(
            $this->makeResolver(FutureResolver::class, $dateChooser)
        );
    }

    public function toTheDate(DateChooserInterface $dateChooser): Engine
    {
        return $this->makeEngine(
            $this->makeResolver(ToDateResolver::class, $dateChooser)
        );
    }

    private function makeResolver(string $concrete, DateChooserInterface $dateChooser): TimeResolverInterface
    {
        return app($concrete, ['dateChooser' => $dateChooser]);
    }

    private function makeEngine(TimeResolverInterface $resolver): Engine
    {
        if (empty($this->timeTravellers)) {
            throw new TimeMachineException("Define at lest one TimeTraveller");
        }

        return app(Engine::class, ['timeTravellers' => $this->timeTravellers, 'resolver' => $resolver]);
    }
}
