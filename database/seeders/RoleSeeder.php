<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_id' => 1, 'role_name' => 'Admin'],
            ['role_id' => 2, 'role_name' => 'Teacher'],
            ['role_id' => 3, 'role_name' => 'Student'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['role_id' => $role['role_id']],
                ['role_name' => $role['role_name']]
            );
        }
    }
}
