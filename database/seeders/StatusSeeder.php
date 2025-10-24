<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            ['name' => 'Новая', 'color' => '#EAD1DC'],
            ['name' => 'В работе', 'color' => '#FFE599'],
            ['name' => 'Ожидает запчасти', 'color' => '#F9CB9C'],
            ['name' => 'Готово', 'color' => '#B6D7A8'],
            ['name' => 'Закрыто', 'color' => '#A4C2F4'],
        ];

        foreach ($rows as $row) {
            Status::updateOrCreate(['name' => $row['name']], ['color' => $row['color']]);
        }
    }
}
