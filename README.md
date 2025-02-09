<div style="text-align: center;">
<h1>TinyFlag ⛳️</h1>
<h2>A dead- simple feature flagging package for Laravel.</h2>
<img style="margin-bottom: 20px;" src="docs/tinyflag.png" alt="TinyFlag" width="400">
</div>

TinyFlag is a simple feature flagging package for Laravel. It allows you to easily manage feature flags in your application.

You do not need to manually declare flags, simply write a check in your code and the flag will be created for you with the given default value.

```php
if(flag('my-feature-flag', true)) {
    // do something
}
```

This will create a feature flag with the name `my-feature-flag` and the default value `true`. You can now enable or disable the flag in the database.

```php
use DynamikDev\TinyFlag\Facades\TinyFlag;

TinyFlag::enable('my-feature-flag');
TinyFlag::disable('my-feature-flag');
```

Or, using the artisan command:

```bash
php artisan flags:enable my-feature-flag
php artisan flags:disable my-feature-flag
```

## Installation

```bash
composer require dynamik-dev/tinyflag
```

After installing, you can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="tiny-flag-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="tiny-flag-config"
```

## Default Flags

To add default flags, you can add them to the `flags` array in the config file.

```php
'flags' => [
    'my-feature-flag' => true,
]
```

This will be the default state of the flag. If the flag is updated in the database, it will override the default value. However, if you reset all flags, the default value will be used again. See more in the [Configuration](docs/configuration.md) section.

## Why not use Laravel Pennant?

Pennant is a great package, but for 90% of my usecases, I just needed a simple boolean check that I could easily manage in the database.

Use Pennant if you need more complex feature flagging, such as per-user flags, or flagging based on a user's role or other attributes.

### Documentation

- [Configuration](docs/configuration.md)
- [Artisan Commands](docs/artisan-commands.md)