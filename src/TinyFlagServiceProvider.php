<?php

namespace DynamikDev\TinyFlag;

use DynamikDev\TinyFlag\Commands\DisableFlag;
use DynamikDev\TinyFlag\Commands\EnableFlag;
use DynamikDev\TinyFlag\Commands\ListFlags;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TinyFlagServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('tiny-flag')
            ->hasConfigFile('tiny-flag')
            ->hasMigration('create_tiny_flag_table')
            ->hasCommands([
                ListFlags::class,
                EnableFlag::class,
                DisableFlag::class,
            ]);
    }
}
