<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Income Categories
            ['name' => 'Sales Revenue', 'code' => 'INC001', 'type' => 'income', 'is_active' => true],
            ['name' => 'Service Revenue', 'code' => 'INC002', 'type' => 'income', 'is_active' => true],
            ['name' => 'Other Income', 'code' => 'INC003', 'type' => 'income', 'is_active' => true],
            
            // Expense Categories
            ['name' => 'Salary & Wages', 'code' => 'EXP001', 'type' => 'expense', 'is_active' => true],
            ['name' => 'Rent', 'code' => 'EXP002', 'type' => 'expense', 'is_active' => true],
            ['name' => 'Utilities', 'code' => 'EXP003', 'type' => 'expense', 'is_active' => true],
            ['name' => 'Office Supplies', 'code' => 'EXP004', 'type' => 'expense', 'is_active' => true],
            ['name' => 'Marketing', 'code' => 'EXP005', 'type' => 'expense', 'is_active' => true],
            ['name' => 'Travel', 'code' => 'EXP006', 'type' => 'expense', 'is_active' => true],
            ['name' => 'Equipment', 'code' => 'EXP007', 'type' => 'expense', 'is_active' => true],
            ['name' => 'Maintenance', 'code' => 'EXP008', 'type' => 'expense', 'is_active' => true],
            ['name' => 'Other Expenses', 'code' => 'EXP009', 'type' => 'expense', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create($category);
        }
    }
}
