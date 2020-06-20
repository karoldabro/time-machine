<?php

namespace Kdabrow\TimeMachine\Contracts;

interface DateChooserInterface
{
    /**
     * Get expresion
     *
     * @return mixed
     */
    public function getTimestamp(): int;
}
