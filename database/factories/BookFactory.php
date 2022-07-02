<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Library;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => Str::uuid()->toString(),
            'library_id' => fn () => self::factoryForModel(Library::class)->create()->id,
            'title' => $this->faker->sentence(),
            'number_of_pages' => $this->faker->numberBetween(0, 250),
            'year_launched' => $this->faker->year(),
        ];
    }
}
