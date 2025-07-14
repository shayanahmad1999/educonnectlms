<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $assignment = Assignment::inRandomOrder()->first() ?? Assignment::factory();
        $student = User::whereHas('role', fn($q) => $q->where('name', 'Student'))->inRandomOrder()->first() ?? User::factory();

        return [
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'submitted_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'file_path' => $this->faker->filePath(),
            'grade' => $this->faker->randomFloat(1, 50, 100),
            'feedback' => $this->faker->sentence(6),
        ];
    }
}
