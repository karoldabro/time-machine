<?php

namespace Kdabrow\TimeMachine;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Result
{
    /**
     * @var array<string, Collection>
     */
    private $successful = [];

    /**
     * @var array<string, Collection>
     */
    private $failed = [];

    public function addSuccessful(Model $result): self
    {
        if (isset($this->successful[get_class($result)]) === false) {
            $this->successful[get_class($result)] = new Collection();
        }

        $this->successful[get_class($result)]->add($result);

        return $this;
    }

    public function addFailed(Model $result): self
    {
        if (isset($this->failed[get_class($result)]) === false) {
            $this->failed[get_class($result)] = new Collection();
        }

        $this->failed[get_class($result)]->add($result);

        return $this;
    }

    /**
     * @return  array<string, Collection>
     */
    public function getAllSuccessful(): array
    {
        return $this->successful;
    }

    public function getSuccessful(string $modelName): Collection
    {
        return $this->successful[$modelName];
    }

    /**
     * @return  array<string, Collection>
     */
    public function getAllFailed(): array
    {
        return $this->failed;
    }

    public function getFailed(string $modelName): Collection
    {
        return $this->failed[$modelName];
    }

    public function isSuccessful(string $modelName): bool
    {
        return isset($this->successful[$modelName]);
    }

    public function isFailed(string $modelName): bool
    {
        return isset($this->failed[$modelName]);
    }
}