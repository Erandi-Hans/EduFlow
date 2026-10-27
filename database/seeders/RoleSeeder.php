<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'role_id' => 1,
                'role_name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => null,
                'updated_by' => null,
                'is_deleted' => false,
            ],
            [
                'role_id' => 2,
                'role_name' => 'Teacher',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => null,
                'updated_by' => null,
                'is_deleted' => false,
            ],
            [
                'role_id' => 3,
                'role_name' => 'Student',
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => null,
                'updated_by' => null,
                'is_deleted' => false,
            ],
        ]);
    }
}
