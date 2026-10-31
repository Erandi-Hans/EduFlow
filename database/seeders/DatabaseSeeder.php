<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin   = Role::firstOrCreate(['role_name' => 'Admin']);
        $teacher = Role::firstOrCreate(['role_name' => 'Teacher']);
        $student = Role::firstOrCreate(['role_name' => 'Student']);

        // Password for all demo accounts: "password"
        User::create([
            'username' => 'admin',
            'email'    => 'admin@eduflow.test',
            'password' => 'password',
            'role_id'  => $admin->role_id,
        ]);

        $tUser = User::create([
            'username' => 'teacher1',
            'email'    => 'teacher@eduflow.test',
            'password' => 'password',
            'role_id'  => $teacher->role_id,
        ]);
        $t = Teacher::create([
            'user_id'       => $tUser->user_id,
            'first_name'    => 'Nimal',
            'last_name'     => 'Perera',
            'phone_number'  => '0771234567',
            'qualification' => 'BSc in Mathematics',
        ]);

        $sUser = User::create([
            'username' => 'student1',
            'email'    => 'student@eduflow.test',
            'password' => 'password',
            'role_id'  => $student->role_id,
        ]);
        $s = Student::create([
            'user_id'       => $sUser->user_id,
            'first_name'    => 'Kasun',
            'last_name'     => 'Silva',
            'date_of_birth' => '2009-05-14',
            'guardian_name' => 'Sunil Silva',
        ]);

        $course = Course::create([
            'course_name' => 'Mathematics Grade 10',
            'course_code' => 'MATH-10',
            'description' => 'Algebra, geometry and basic statistics.',
            'teacher_id'  => $t->teacher_id,
        ]);
        $course->Student()->attach($s->student_id);

        Assignment::create([
            'title'       => 'Algebra Worksheet 1',
            'description' => 'Solve all questions and upload a PDF or photo of your work.',
            'due_date'    => now()->addDays(7),
            'course_id'   => $course->course_id,
        ]);



        $this->call([
            RoleSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
        ]);
    }
}
