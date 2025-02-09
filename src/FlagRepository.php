<?php

namespace DynamikDev\TinyFlag;

use DynamikDev\TinyFlag\Enums\FlagState;
use DynamikDev\TinyFlag\Models\Flag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class FlagRepository
{
    public ?array $events = null;

    public function __construct()
    {
        $this->events = config('tiny-flag.events') ?? [];
    }

    public function model(): Flag
    {
        /** @var Flag */
        $model = app(config('tiny-flag.model', Flag::class));

        return $model;
    }

    public function cachKey(string $name = ''): string
    {
        return config('tiny-flag.cache_prefix').str()->slug($name);
    }

    public function resolveState(bool|string|FlagState $state): FlagState
    {
        if (is_bool($state)) {
            return $state ? FlagState::ACTIVE : FlagState::INACTIVE;
        }

        if (is_string($state)) {
            return FlagState::from($state);
        }

        return $state;
    }

    public function query(): Builder
    {
        return $this->model()::query();
    }

    public function add(string $name, bool|string|FlagState $state): void
    {

        $exists = $this->model()::where('name', $name)->exists();

        $flag = $this->model()::firstOrCreate([
            'name' => $name,
            'state' => $this->resolveState($state),
        ]);

        if (! $exists) {
            event(new $this->events['created']($flag));
        }

        if (config('tiny-flag.cache.enabled')) {
            Cache::put($this->cachKey($flag->name), $flag->state);
        }
    }

    public function update(string $name, bool|string|FlagState $state): void
    {
        $originalState = $this->resolveState($this->get($name));
        $flag = $this->model()::where('name', $name)->first();
        $flag->state = $this->resolveState($state);
        $flag->save();

        if ($originalState !== $flag->state) {
            if ($originalState === FlagState::ACTIVE) {
                event(new $this->events['disabled']($flag));
            } else {
                event(new $this->events['enabled']($flag));
            }
        }

        if (config('tiny-flag.cache.enabled')) {
            Cache::put($this->cachKey($flag->name), $flag->state);
        }
    }

    public function get(string $name, bool $default = false): bool
    {
        $state = (config('tiny-flag.cache.enabled')
            ? Cache::get($this->cachKey($name)) : null)
            ?? $this->model()::where('name', $name)->first()?->state;

        if (! $state) {
            $this->add($name, $default);

            return $default;
        }

        return $state === FlagState::ACTIVE;
    }

    public function all(): Collection
    {
        return $this->model()::all();
    }

    public function delete(string $name): void
    {
        $this->model()::where('name', $name)->delete();

        if (config('tiny-flag.cache.enabled')) {
            Cache::forget($this->cachKey($name));
        }
    }

    public function flush(): void
    {
        if (config('tiny-flag.cache.enabled')) {
            Cache::forget(config('tiny-flag.cache_prefix'));
        }

        $this->model()::truncate();
    }
}
