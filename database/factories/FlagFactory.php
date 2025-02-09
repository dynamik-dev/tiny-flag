<?php

namespace DynamikDev\TinyFlag\Database\Factories;

use DynamikDev\TinyFlag\Enums\FlagState;
use DynamikDev\TinyFlag\Models\Flag;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlagFactory extends Factory
{
    protected $model = null;

    public function __construct($count = null, $state = null)
    {
        parent::__construct($count, $state);
        $this->model = config('tiny-flag.model', Flag::class);
    }

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'state' => FlagState::ACTIVE,
        ];
    }
}
