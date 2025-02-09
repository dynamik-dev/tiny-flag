<?php

namespace DynamikDev\TinyFlag\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DynamikDev\TinyFlag\TinyFlag
 */
class TinyFlag extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DynamikDev\TinyFlag\TinyFlag::class;
    }
}
