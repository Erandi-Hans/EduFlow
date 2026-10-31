<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds to create 50 student accounts 
     * with realistic profile details and profile photos.
     */
    public function run(): void
    {
        // Retrieve or create the 'Student' role to assign foreign key relation
        $studentRole = Role::firstOrCreate(['role_name' => 'Student']);

        // Arrays of realistic first names and last names for student generation
        $firstNames = [
            'Akalanka',
            'Bimsara',
            'Chamod',
            'Danushka',
            'Erandi',
            'Fathima',
            'Gayan',
            'Hashan',
            'Ishara',
            'Janith',
            'Kavinda',
            'Lakshan',
            'Madusha',
            'Nimesh',
            'Oshada',
            'Pasindu',
            'Roshan',
            'Sachin',
            'Tharindu',
            'Visal',
            'Anodya',
            'Chathurani',
            'Dilhani',
            'Hashini',
            'Imesha',
            'Kavindya',
            'Madushika',
            'Nirosha',
            'Pabasara',
            'Sachini'
        ];

        $lastNames = [
            'Perera',
            'Silva',
            'Fernando',
            'Jayasinghe',
            'Kumara',
            'Bandara',
            'Rajapaksha',
            'Gunawardena',
            'Rathnayake',
            'Wickramasinghe',
            'Herath',
            'Senanayake',
            'Amarasinghe',
            'Dissanayake',
            'Fonseka',
            'Gamage',
            'Liyanage',
            'Peiris',
            'Weerasinghe',
            'Karunaratne'
        ];

        // Guardian name prefixes for realism
        $guardianTitles = ['Mr. ', 'Mrs. ', 'Dr. '];

        // Loop to seed 50 student records
        for ($i = 1; $i <= 50; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];

            // Generate a unique username and institutional email
            $username = 'student' . $i;
            $email = strtolower($firstName) . '.' . strtolower($lastName) . $i . '@eduflow.test';

            // 1. Create the base user record for authentication and credentials
            $user = User::create([
                'username'      => $username,
                'email'         => $email,
                'password'      => Hash::make('password'), // Default demo password
                'role_id'       => $studentRole->role_id,
                // Using Pravatar image URLs mapped with distinct IDs for student profile photos
                'profile_photo' => 'https://i.pravatar.cc/300?img=' . (($i % 70) + 1),
            ]);

            // 2. Create the associated student record matching the form fields
            Student::create([
                'user_id'       => $user->user_id,
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                // Generate realistic student birth dates (approx ages 14-18)
                'date_of_birth' => '200' . rand(6, 9) . '-' . sprintf("%02d", rand(1, 12)) . '-' . sprintf("%02d", rand(1, 28)),
                'guardian_name' => $guardianTitles[array_rand($guardianTitles)] . $lastNames[array_rand($lastNames)],
            ]);
        }
    }
}
