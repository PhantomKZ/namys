<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        $clothes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        foreach ($clothes as $name) {
            Size::updateOrCreate(
                ['name' => $name],
                ['category' => 'clothes']      // уберите, если поля category нет
            );
        }

        /* -------- 2. размеры обуви (36-46) -------- */
        foreach (range(36, 46) as $num) {
            Size::updateOrCreate(
                ['name' => (string) $num],
                ['category' => 'shoes']        // уберите, если поля category нет
            );
        }
    }
}

