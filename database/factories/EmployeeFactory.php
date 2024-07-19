<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'position_id' => \App\Models\JobTitle::inRandomOrder()->first()->id,
            'name' => fake()->name(),
            'nip' => fake()->ean8(),
            'photo' => null,
            'start_date' => fake()->dateTime(),
        ];
    }
}
