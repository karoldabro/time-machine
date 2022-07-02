<?php

namespace Kdabrow\TimeMachine\Contracts;

interface DateChooserInterface
{
    /**
     * @return mixed
     */
    public function getTimestamp(): int;
}
