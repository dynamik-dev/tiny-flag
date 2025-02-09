<?php

return [

    /**
     * The table name to store the flags in.
     */
    'table' => 'tiny_flags',

    /**
     * The model to use for the flags.
     */
    'model' => \DynamikDev\TinyFlag\Models\Flag::class,

    /**
     * Flags to initialize with
     * their default state.
     *
     * This state may change, but these
     * are the initial states.
     */
    'flags' => [
        // 'my-test-feature' => true,
    ],

    /**
     * Cache configuration.
     */
    'cache' => [
        'enabled' => env('TINY_FLAG_CACHE_ENABLED', true),
        'prefix' => env('TINY_FLAG_CACHE_PREFIX', 'tiny-flag'),
    ],

    'events' => [

        /**
         * Created event - when a flag is created.
         */
        'created' => \DynamikDev\TinyFlag\Events\FlagCreated::class,

        /**
         * Enabled event - when a flag is enabled.
         */
        'enabled' => \DynamikDev\TinyFlag\Events\FlagEnabled::class,

        /**
         * Disabled event - when a flag is disabled.
         */
        'disabled' => \DynamikDev\TinyFlag\Events\FlagDisabled::class,

        /**
         * Removed event - when a flag is removed.
         */
        'removed' => \DynamikDev\TinyFlag\Events\FlagRemoved::class,
    ],
];
