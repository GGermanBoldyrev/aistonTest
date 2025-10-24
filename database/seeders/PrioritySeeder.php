<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        Priority::query()->delete();

        $rows = [
            [
                'name' => 'Низкий',
                'color' => '#2196F3',
                'description' => 'Не важно'
            ],
            [
                'name' => 'Средний',
                'color' => '#FFC107',
                'description' => 'В теории важно'
            ],
            [
                'name' => 'Высокий',
                'color' => '#FF9800',
                'description' => 'Вважно'
            ],
            [
                'name' => 'Критичный',
                'color' => '#F44336',
                'description' => 'Очень важно!'
            ],
        ];

        foreach ($rows as $row) {
            Priority::create($row);
        }
    }
}
