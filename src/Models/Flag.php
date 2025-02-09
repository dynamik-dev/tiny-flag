<?php

namespace DynamikDev\TinyFlag\Models;

use DynamikDev\TinyFlag\Database\Factories\FlagFactory;
use DynamikDev\TinyFlag\Enums\FlagState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property FlagState $state
 *
 * @method static Builder query()
 *
 * @property-read int $id
 * @property mixed $name
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * @method static FlagFactory factory($count = null, $state = [])
 * @method static Builder each(callable $callback)
 */
class Flag extends Model
{
    use HasFactory;

    protected $table = null;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('tiny-flag.table', 'tiny_flags');
    }

    protected $guarded = ['id'];

    protected $casts = [
        'state' => FlagState::class,
    ];

    public static function newFactory(): FlagFactory
    {
        return FlagFactory::new();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('state', FlagState::ACTIVE);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('state', FlagState::INACTIVE);
    }

    public static function init(string $name, FlagState|string|bool $value): self
    {
        if (is_bool($value)) {
            $value = $value ? FlagState::ACTIVE : FlagState::INACTIVE;
        }

        if (is_string($value)) {
            $value = FlagState::from($value);
        }

        if (! $value instanceof FlagState) {
            throw new \InvalidArgumentException('Invalid value for initial value');
        }

        return self::firstOrCreate([
            'name' => $name,
            'state' => $value,
        ]);
    }
}
