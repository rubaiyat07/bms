<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Admin gets all permissions
        $admin = Role::where('name', 'admin')->first();
        $allPermissions = Permission::all();
        $admin->permissions()->attach($allPermissions);

        // Branch Manager permissions
        $branchManager = Role::where('name', 'branch_manager')->first();
        $branchManagerPermissions = Permission::whereIn('name', [
            'branch.view',
            'user.view',
            'user.create',
            'user.update',
            'project.view',
            'project.create',
            'project.update',
            'project.delete',
            'inventory.view',
            'inventory.create',
            'inventory.update',
            'inventory.delete',
            'transaction.view',
            'transaction.create',
            'transaction.update',
            'transaction.approve',
            'dashboard.view',
            'message.view',
            'message.send',
            'announcement.view',
        ])->get();
        $branchManager->permissions()->attach($branchManagerPermissions);

        // Staff permissions
        $staff = Role::where('name', 'staff')->first();
        $staffPermissions = Permission::whereIn('name', [
            'branch.view',
            'project.view',
            'inventory.view',
            'transaction.view',
            'transaction.create',
            'dashboard.view',
            'message.view',
            'message.send',
            'announcement.view',
        ])->get();
        $staff->permissions()->attach($staffPermissions);
    }
}
