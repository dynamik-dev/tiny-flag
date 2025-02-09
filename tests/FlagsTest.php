<?php

use DynamikDev\TinyFlag\Enums\FlagState;
use DynamikDev\TinyFlag\Facades\TinyFlag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->events = config('tiny-flag.events');
    Event::fake();
});

it('can init a flag to true', function () {
    // enable cache
    config(['tiny-flag.cache.enabled' => true]);

    flag('my-test-feature', true);
    expect(flag('my-test-feature'))->toBeTrue();

    assertDatabaseHas('tiny_flags', [
        'name' => 'my-test-feature',
        'state' => FlagState::ACTIVE,
    ]);

    // Assert that the event was dispatched
    Event::assertDispatched($this->events['created'], function ($event) {
        return $event->flag->name === 'my-test-feature';
    });

    // make sure the cache is hit
    $cacheKey = tiny_flag()->repository()->cachKey('my-test-feature');
    expect(Cache::get($cacheKey))->toBe(FlagState::ACTIVE);
});

it('can init a flag to false', function () {
    flag('my-test-feature');
    expect(flag('my-test-feature'))->toBeFalse();
    assertDatabaseHas('tiny_flags', [
        'name' => 'my-test-feature',
        'state' => FlagState::INACTIVE,
    ]);
    Event::assertDispatched($this->events['created'], function ($event) {
        return $event->flag->name === 'my-test-feature';
    });
});

it('can disable a flag', function () {
    flag('my-test-feature', true);
    tiny_flag()->disable('my-test-feature');
    expect(flag('my-test-feature'))->toBeFalse();

    assertDatabaseHas('tiny_flags', [
        'name' => 'my-test-feature',
        'state' => FlagState::INACTIVE,
    ]);

    Event::assertDispatched($this->events['disabled'], function ($event) {
        return $event->flag->name === 'my-test-feature';
    });
});

it('can enable a flag', function () {
    flag('my-test-feature');
    tiny_flag()->enable('my-test-feature');
    expect(flag('my-test-feature'))->toBeTrue();

    assertDatabaseHas('tiny_flags', [
        'name' => 'my-test-feature',
        'state' => FlagState::ACTIVE,
    ]);

    Event::assertDispatched($this->events['enabled'], function ($event) {
        return $event->flag->name === 'my-test-feature';
    });
});

it('can flush all flags', function () {
    flag('my-test-feature', true);
    flag('my-test-feature-2', false);
    tiny_flag()->flush();
    expect(tiny_flag()->all())->toBeEmpty();
});

it('can get all flags', function () {
    flag('my-test-feature', true);
    flag('my-test-feature-2', false);
    expect(TinyFlag::all())->toHaveLength(2)->toBeCollection();
});

it('can get all enabled flags', function () {
    flag('my-test-feature', true);
    flag('my-test-feature-2', false);
    expect(TinyFlag::enabled())->toHaveLength(1)->toBeCollection();
});

it('can get all disabled flags', function () {
    flag('my-test-feature', true);
    flag('my-test-feature-2', false);
    expect(TinyFlag::disabled())->toHaveLength(1)->toBeCollection();
});

it('can reset a flag', function () {
    config(['tiny-flag.flags' => ['my-test-feature' => true]]);
    TinyFlag::disable('my-test-feature');
    tiny_flag()->reset('my-test-feature');
    expect(flag('my-test-feature'))->toBeTrue();
});

it('can reset all flags', function () {
    config(['tiny-flag.flags' => [
        'my-test-feature' => true,
        'my-test-feature-2' => true,
    ]]);
    TinyFlag::disable('my-test-feature');
    TinyFlag::disable('my-test-feature-2');
    tiny_flag()->resetAll();
    expect(flag('my-test-feature'))->toBeTrue();
    expect(flag('my-test-feature-2'))->toBeTrue();
});
