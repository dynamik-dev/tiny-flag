<?php

use DynamikDev\TinyFlag\TinyFlag;

if (! function_exists('tiny_flag')) {

    function tiny_flag(): TinyFlag
    {
        /** @var TinyFlag */
        $tinyFlag = app(TinyFlag::class);

        return $tinyFlag;
    }
}

if (! function_exists('flag')) {
    function flag(string $name, ?bool $default = null): bool
    {
        /** @var TinyFlag */
        $tinyFlag = app(TinyFlag::class);

        return $tinyFlag->isActive($name, $default);
    }
}
