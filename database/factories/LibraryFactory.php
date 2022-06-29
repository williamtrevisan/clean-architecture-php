<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Library>
 */
class LibraryFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => Str::uuid()->toString(),
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
        ];
    }
}
