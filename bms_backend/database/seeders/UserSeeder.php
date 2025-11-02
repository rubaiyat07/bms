<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@bms.com',
            'password' => Hash::make('password'),
            'phone' => '+8801700000000',
            'employee_id' => 'EMP001',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole);

        // Create Branch Manager (will be assigned to branch later)
        $manager = User::create([
            'name' => 'Branch Manager',
            'email' => 'manager@bms.com',
            'password' => Hash::make('password'),
            'phone' => '+8801711111111',
            'employee_id' => 'EMP002',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $managerRole = Role::where('name', 'branch_manager')->first();
        $manager->roles()->attach($managerRole);

        // Create Staff User
        $staff = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@bms.com',
            'password' => Hash::make('password'),
            'phone' => '+8801722222222',
            'employee_id' => 'EMP003',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $staffRole = Role::where('name', 'staff')->first();
        $staff->roles()->attach($staffRole);
    }
}
