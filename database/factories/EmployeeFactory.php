<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_number' => fake()->unique()->numerify('EMP####'),
            'name' => fake('ja_JP')->name(),
            'department' => fake()->randomElement(['営業部', '開発部', '管理部', '人事部']),
            'joined_at' => fake()->dateTimeBetween('-15 years', 'now')->format('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
