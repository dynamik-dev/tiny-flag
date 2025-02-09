<?php

namespace DynamikDev\TinyFlag\Events;

use DynamikDev\TinyFlag\Models\Flag;
use Illuminate\Foundation\Events\Dispatchable;

abstract class FlagEvent
{
    use Dispatchable;

    public function __construct(public Flag $flag) {}
}
