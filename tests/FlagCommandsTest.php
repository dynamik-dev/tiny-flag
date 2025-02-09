<?php

use DynamikDev\TinyFlag\Enums\FlagState;
use DynamikDev\TinyFlag\Facades\TinyFlag;

beforeEach(function () {
    // Clear the flags table before each test
    TinyFlag::flush();
});

test('it displays empty message when no flags exist', function () {
    $this->artisan('flags:list')
        ->expectsOutput('No flags found.')
        ->assertSuccessful();
});

test('it lists flags in table format', function () {

    flag('feature_one', true);
    flag('feature_two', false);

    $this->artisan('flags:list')
        ->expectsTable(
            ['ID', 'Flag Name', 'State'],
            [
                [1, 'feature_one', 'Active'],
                [2, 'feature_two', 'Inactive'],
            ]
        )
        ->assertSuccessful();
});

test('it filters active flags', function () {
    $flag1 = TinyFlag::factory()->create(['name' => 'feature_one', 'state' => FlagState::ACTIVE]);
    TinyFlag::factory()->create(['name' => 'feature_two', 'state' => FlagState::INACTIVE]);

    $this->artisan('flags:list --state=active')
        ->expectsTable(
            ['ID', 'Flag Name', 'State'],
            [
                [$flag1->id, 'feature_one', 'Active'],
            ]
        )
        ->assertSuccessful();
});

test('it filters inactive flags', function () {
    TinyFlag::factory()->create(['name' => 'feature_one', 'state' => FlagState::ACTIVE]);
    $flag2 = TinyFlag::factory()->create(['name' => 'feature_two', 'state' => FlagState::INACTIVE]);

    $this->artisan('flags:list --state=inactive')
        ->expectsTable(
            ['ID', 'Flag Name', 'State'],
            [
                [$flag2->id, 'feature_two', 'Inactive'],
            ]
        )
        ->assertSuccessful();
});

test('it accepts alternative state values', function () {
    $flag1 = TinyFlag::factory()->create(['name' => 'feature_one', 'state' => FlagState::ACTIVE]);

    // Test 'true' as alternative for active
    $this->artisan('flags:list --state=true')
        ->expectsTable(
            ['ID', 'Flag Name', 'State'],
            [
                [$flag1->id, 'feature_one', 'Active'],
            ]
        )
        ->assertSuccessful();
});

test('it can disable a flag', function () {
    flag('feature_one', true);

    $this->artisan('flags:disable feature_one')
        ->assertSuccessful();

    expect(flag('feature_one'))->toBeFalse();
});

test('it can enable a flag', function () {
    flag('feature_one', false);

    $this->artisan('flags:enable feature_one')
        ->assertSuccessful();

    expect(flag('feature_one'))->toBeTrue();
});
