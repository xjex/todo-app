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
        $dueDate = fake()->optional()->dateTimeBetween('now', '+30 days');
        
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['Work', 'Personal', 'Shopping', 'Health', null]),
            'due_date' => $dueDate ? $dueDate->format('Y-m-d') : null,
            'completed' => fake()->boolean(20),
        ];
    }
}

