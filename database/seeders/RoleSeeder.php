<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Проверка, чтобы не создать дубликаты
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }
        if (!Role::where('name', 'manager')->exists()) {
            Role::create(['name' => 'manager']);
        }
        if (!Role::where('name', 'client')->exists()) {
            Role::create(['name' => 'client']);
        }
    }
}
