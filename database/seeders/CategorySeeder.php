<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            'Кассы',
            'Холодильники',
            'Кондиционеры',
            'ИТ',
            'Сантехника',
            'Измерительное оборудование',
            'Помещения',
        ];

        foreach ($rows as $row) {
            Category::updateOrCreate(
                ['name' => $row]
            );
        }
    }
}
