<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'name' => 'Низкий',
                'color' => '#2196F3'
            ],
            [
                'name' => 'Средний',
                'color' => '#FFC107'
            ],
            [
                'name' => 'Высокий',
                'color' => '#FF9800'
            ],
            [
                'name' => 'Критичный',
                'color' => '#F44336'
            ],
        ];

        foreach ($rows as $row) {
            Priority::updateOrCreate(
                ['name' => $row['name']],
                ['color' => $row['color']]
            );
        }
    }
}
