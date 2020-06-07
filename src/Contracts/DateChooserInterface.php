<?php

namespace Kdabrow\TimeMachine\Contracts;

interface DateChooserInterface
{
    /**
     * Get number of seconds
     *
     * @return  int
     */
    public function getTimestamp(): int;
}
