<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // first get and create Teacher role 
        $teacherRole = Role::firstOrCreate(['role_name' => 'Teacher']);

        $teachersData = [
            ['first_name' => 'Sunil', 'last_name' => 'Fernando', 'qualification' => 'MSc in Information Technology'],
            ['first_name' => 'Kamal', 'last_name' => 'Silva', 'qualification' => 'BSc in ICT (Hons)'],
            ['first_name' => 'Anura', 'last_name' => 'Jayasinghe', 'qualification' => 'PhD in Software Engineering'],
            ['first_name' => 'Saman', 'last_name' => 'Kumara', 'qualification' => 'BSc in Physical Science'],
            ['first_name' => 'Kasun', 'last_name' => 'Rajapaksha', 'qualification' => 'BIT (UCSC)'],
            ['first_name' => 'Ruwan', 'last_name' => 'Dissanayake', 'qualification' => 'MSc in Computer Networks'],
            ['first_name' => 'Chathura', 'last_name' => 'Gunawardena', 'qualification' => 'BSc in Data Science'],
            ['first_name' => 'Dhanushka', 'last_name' => 'Wickramasinghe', 'qualification' => 'BSc in ICT'],
            ['first_name' => 'Tharindu', 'last_name' => 'Bandara', 'qualification' => 'MSc in Cyber Security'],
            ['first_name' => 'Dilini', 'last_name' => 'Fonseka', 'qualification' => 'BSc in Software Engineering'],
            ['first_name' => 'Thilini', 'last_name' => 'Gamage', 'qualification' => 'MSc in Data Analytics'],
            ['first_name' => 'Nirosha', 'last_name' => 'Rathnayake', 'qualification' => 'BSc in Computing'],
            ['first_name' => 'Kavindi', 'last_name' => 'De Silva', 'qualification' => 'BSc in ICT (Hons)'],
            ['first_name' => 'Hashini', 'last_name' => 'Herath', 'qualification' => 'MSc in Information Systems'],
            ['first_name' => 'Piumi', 'last_name' => 'Amarasinghe', 'qualification' => 'BSc in Computer Science'],
            ['first_name' => 'Sachini', 'last_name' => 'Wijesinghe', 'qualification' => 'BIT (UCSC)'],
            ['first_name' => 'Rashmi', 'last_name' => 'Liyanage', 'qualification' => 'BSc in Information Technology'],
            ['first_name' => 'Nipuni', 'last_name' => 'Senanayake', 'qualification' => 'MSc in Software Engineering'],
            ['first_name' => 'Sanduni', 'last_name' => 'Peiris', 'qualification' => 'BSc in ICT'],
        ];

        foreach ($teachersData as $index => $data) {
            $id = $index + 2; // we have one teacher and that is why we start with two

            // 1.first create user , with loging details 
            $user = User::create([
                'username'      => 'teacher' . $id,
                'email'         => strtolower($data['first_name']) . '.' . strtolower($data['last_name']) . '@eduflow.test',
                'password'      => Hash::make('password'),
                'role_id'       => $teacherRole->role_id,
                'profile_photo' => 'https://i.pravatar.cc/300?img=' . ($id + 10),
            ]);

            //2.After the get user_id ,use them and add details into teacher table
            Teacher::create([
                'user_id'       => $user->user_id,
                'first_name'    => $data['first_name'],
                'last_name'     => $data['last_name'],
                'phone_number'  => '+947' . rand(0, 8) . sprintf("%07d", rand(0, 9999999)),
                'qualification' => $data['qualification'],
            ]);
        }
    }
}
