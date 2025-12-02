<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    protected $model = Todo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['Work', 'Personal', 'Shopping', 'Health', null]),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'completed' => fake()->boolean(20),
        ];
    }
}

