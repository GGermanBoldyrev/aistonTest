<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::query()->delete();

        $categories = [
            ['name' => 'Холодильники'],
            ['name' => 'Кондиционеры'],
            ['name' => 'Сантехника'],
            ['name' => 'Компьютеры и ПО'],
            ['name' => 'Кассовое оборудование'],
            ['name' => 'Помещения'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
