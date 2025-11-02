<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Branch Management
            ['name' => 'branch.view', 'display_name' => 'View Branches', 'module' => 'branch'],
            ['name' => 'branch.create', 'display_name' => 'Create Branch', 'module' => 'branch'],
            ['name' => 'branch.update', 'display_name' => 'Update Branch', 'module' => 'branch'],
            ['name' => 'branch.delete', 'display_name' => 'Delete Branch', 'module' => 'branch'],
            
            // User Management
            ['name' => 'user.view', 'display_name' => 'View Users', 'module' => 'user'],
            ['name' => 'user.create', 'display_name' => 'Create User', 'module' => 'user'],
            ['name' => 'user.update', 'display_name' => 'Update User', 'module' => 'user'],
            ['name' => 'user.delete', 'display_name' => 'Delete User', 'module' => 'user'],
            
            // Role & Permission Management
            ['name' => 'role.view', 'display_name' => 'View Roles', 'module' => 'role'],
            ['name' => 'role.create', 'display_name' => 'Create Role', 'module' => 'role'],
            ['name' => 'role.update', 'display_name' => 'Update Role', 'module' => 'role'],
            ['name' => 'role.delete', 'display_name' => 'Delete Role', 'module' => 'role'],
            
            // Project Management
            ['name' => 'project.view', 'display_name' => 'View Projects', 'module' => 'project'],
            ['name' => 'project.create', 'display_name' => 'Create Project', 'module' => 'project'],
            ['name' => 'project.update', 'display_name' => 'Update Project', 'module' => 'project'],
            ['name' => 'project.delete', 'display_name' => 'Delete Project', 'module' => 'project'],
            
            // Inventory Management
            ['name' => 'inventory.view', 'display_name' => 'View Inventory', 'module' => 'inventory'],
            ['name' => 'inventory.create', 'display_name' => 'Create Inventory Item', 'module' => 'inventory'],
            ['name' => 'inventory.update', 'display_name' => 'Update Inventory Item', 'module' => 'inventory'],
            ['name' => 'inventory.delete', 'display_name' => 'Delete Inventory Item', 'module' => 'inventory'],
            
            // Finance Management
            ['name' => 'transaction.view', 'display_name' => 'View Transactions', 'module' => 'finance'],
            ['name' => 'transaction.create', 'display_name' => 'Create Transaction', 'module' => 'finance'],
            ['name' => 'transaction.update', 'display_name' => 'Update Transaction', 'module' => 'finance'],
            ['name' => 'transaction.delete', 'display_name' => 'Delete Transaction', 'module' => 'finance'],
            ['name' => 'transaction.approve', 'display_name' => 'Approve Transaction', 'module' => 'finance'],
            
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'module' => 'dashboard'],
            ['name' => 'dashboard.view_all', 'display_name' => 'View All Branches Dashboard', 'module' => 'dashboard'],
            
            // Messages
            ['name' => 'message.view', 'display_name' => 'View Messages', 'module' => 'message'],
            ['name' => 'message.send', 'display_name' => 'Send Message', 'module' => 'message'],
            ['name' => 'message.delete', 'display_name' => 'Delete Message', 'module' => 'message'],
            
            // Announcements
            ['name' => 'announcement.view', 'display_name' => 'View Announcements', 'module' => 'announcement'],
            ['name' => 'announcement.create', 'display_name' => 'Create Announcement', 'module' => 'announcement'],
            ['name' => 'announcement.update', 'display_name' => 'Update Announcement', 'module' => 'announcement'],
            ['name' => 'announcement.delete', 'display_name' => 'Delete Announcement', 'module' => 'announcement'],
            
            // Audit Logs
            ['name' => 'audit.view', 'display_name' => 'View Audit Logs', 'module' => 'audit'],
            
            // Settings
            ['name' => 'setting.view', 'display_name' => 'View Settings', 'module' => 'setting'],
            ['name' => 'setting.update', 'display_name' => 'Update Settings', 'module' => 'setting'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
