<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ------------------------
        // Create Roles
        // ------------------------
        $adminRole = Role::factory()->create(['name' => 'Admin']);
        $instructorRole = Role::factory()->create(['name' => 'Instructor']);
        $studentRole = Role::factory()->create(['name' => 'Student']);

        // ------------------------
        // Create Users
        // ------------------------
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' =>  $adminRole->id,
            'email_verified_at' => now(),
        ]);
        $instructors = User::factory(10)->create(['role_id' => $instructorRole->id]);
        $students = User::factory(50)->create(['role_id' => $studentRole->id]);

        // ------------------------
        // Create Courses
        // ------------------------
        $courses = Course::factory(20)->create();

        // ------------------------
        // Enroll Students in Courses
        // ------------------------
        foreach ($courses as $course) {
            $course->students()->attach(
                $students->random(rand(5, 15))->pluck('id')->toArray()
            );
        }

        // ------------------------
        // Create Assignments for Each Course
        // ------------------------
        foreach ($courses as $course) {
            Assignment::factory(rand(2, 4))->create([
                'course_id' => $course->id,
            ]);
        }

        // ------------------------
        // Re-fetch Assignments with Eager Loaded Course & Students
        // ------------------------
        $assignments = Assignment::with('course.students')->get();

        // ------------------------
        // Create Submissions
        // ------------------------
        foreach ($assignments as $assignment) {
            $course = $assignment->course;

            if (!$course || !$course->students || $course->students->isEmpty()) {
                continue; // skip if no students enrolled
            }

            $courseStudents = $course->students;
            $take = min(3, $courseStudents->count());

            foreach ($courseStudents->random($take) as $student) {
                Submission::factory()->create([
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                ]);
            }
        }
    }
}
