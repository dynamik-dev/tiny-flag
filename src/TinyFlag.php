<?php

namespace DynamikDev\TinyFlag;

use DynamikDev\TinyFlag\Database\Factories\FlagFactory;
use DynamikDev\TinyFlag\Enums\FlagState;
use DynamikDev\TinyFlag\Models\Flag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TinyFlag
{
    public function __construct(protected FlagRepository $repository) {}

    public function repository(): FlagRepository
    {
        return $this->repository;
    }

    public function factory($count = null, $state = []): FlagFactory
    {
        return $this->repository()->model()->factory($count, $state);
    }

    public function isActive(string $name, ?bool $default = null): bool
    {
        return $this->repository()->get($name, $default ?? false);
    }

    public function disable(string $name): void
    {
        $this->repository()->update($name, FlagState::INACTIVE);
    }

    public function enable(string $name): void
    {
        $this->repository()->update($name, FlagState::ACTIVE);
    }

    public function all(): Collection
    {
        return $this->repository()->all();
    }

    public function flush(): void
    {
        $this->repository()->flush();
    }

    public function reset(string $name): void
    {
        $defaultFlags = config('tiny-flag.flags', []);

        if ($defaultValue = data_get($defaultFlags, $name)) {
            $state = $this->repository()->resolveState($defaultValue);
            $this->repository()->update($name, $state);
        }
    }

    public function resetAll(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int,\DynamikDev\TinyFlag\Models\Flag> */
        $flags = $this->all();
        $flags->each(function (Flag $flag): void {
            $this->reset($flag->name);
        });
    }

    public function enabled(): Collection
    {
        return $this->repository()->query()->where('state', FlagState::ACTIVE)->get();
    }

    public function disabled(): Collection
    {
        return $this->repository()->query()->where('state', FlagState::INACTIVE)->get();
    }

    public function query(): Builder
    {
        return $this->repository()->query();
    }
}
