<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name', 'value' => 'BMS', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_name', 'value' => 'Business Management System', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_email', 'value' => 'info@bms.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_phone', 'value' => '+8801700000000', 'type' => 'string', 'group' => 'general'],
            ['key' => 'currency', 'value' => 'BDT', 'type' => 'string', 'group' => 'general'],
            ['key' => 'timezone', 'value' => 'Asia/Dhaka', 'type' => 'string', 'group' => 'general'],
            ['key' => 'date_format', 'value' => 'Y-m-d', 'type' => 'string', 'group' => 'general'],
            ['key' => 'enable_notifications', 'value' => 'true', 'type' => 'boolean', 'group' => 'notification'],
            ['key' => 'enable_email_notifications', 'value' => 'true', 'type' => 'boolean', 'group' => 'notification'],
            ['key' => 'low_stock_threshold', 'value' => '10', 'type' => 'integer', 'group' => 'inventory'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
