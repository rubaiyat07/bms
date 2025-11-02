<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::where('email', 'manager@bms.com')->first();

        $branches = [
            [
                'name' => 'Head Office',
                'code' => 'HO',
                'address' => 'Dhaka, Bangladesh',
                'contact_email' => 'ho@bms.com',
                'contact_phone' => '+8801700000001',
                'manager_id' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Dhaka Branch',
                'code' => 'DHK',
                'address' => 'Gulshan, Dhaka',
                'contact_email' => 'dhaka@bms.com',
                'contact_phone' => '+8801700000002',
                'manager_id' => $manager->id,
                'status' => 'active',
            ],
            [
                'name' => 'Chittagong Branch',
                'code' => 'CTG',
                'address' => 'Agrabad, Chittagong',
                'contact_email' => 'chittagong@bms.com',
                'contact_phone' => '+8801700000003',
                'manager_id' => null,
                'status' => 'active',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }

        // Update manager's branch
        if ($manager) {
            $dhakaBranch = Branch::where('code', 'DHK')->first();
            $manager->update(['branch_id' => $dhakaBranch->id]);
        }

        // Update staff's branch
        $staff = User::where('email', 'staff@bms.com')->first();
        if ($staff) {
            $dhakaBranch = Branch::where('code', 'DHK')->first();
            $staff->update(['branch_id' => $dhakaBranch->id]);
        }
    }
}
