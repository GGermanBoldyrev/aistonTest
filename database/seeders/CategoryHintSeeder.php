<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryHint;
use Illuminate\Database\Seeder;

class CategoryHintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoryHint::query()->delete();

        $categories = Category::pluck('id', 'name');

        $hintsData = [
            'Холодильники' => [
                'positive' => [
                    'температура в камере выше допустимой нормы (+2...+8 °С) и не восстанавливается;',
                    'оборудование издаёт необычные шумы (гул, стук, вибрация);',
                    'есть ошибка на дисплее или аварийный сигнал;',
                    'дверь не закрывается/сломаны уплотнители;',
                    'холодильник не включается или выключается самопроизвольно.',
                ],
                'negative' => [
                    'просто загружено много товара, и температура временно повысилась;',
                    'дверь была оставлена открытой, и холодильник «догоняет» температуру;',
                    'требуется только разморозка (согласно регламенту её выполняет персонал аптеки).',
                ],
            ],
            'Кондиционеры' => [
                'positive' => [
                    'не охлаждает воздух;',
                    'течет вода из внутреннего блока;',
                    'не реагирует на пульт ДУ.',
                ],
                'negative' => [
                    'нужно почистить фильтры (согласно инструкции);',
                    'сели батарейки в пульте.',
                ],
            ],
        ];

        foreach ($hintsData as $categoryName => $hints) {
            if (!isset($categories[$categoryName])) {
                continue;
            }

            $categoryId = $categories[$categoryName];

            foreach ($hints['positive'] as $text) {
                CategoryHint::create([
                    'category_id' => $categoryId,
                    'text' => $text,
                    'hint_type' => 'positive',
                ]);
            }

            foreach ($hints['negative'] as $text) {
                CategoryHint::create([
                    'category_id' => $categoryId,
                    'text' => $text,
                    'hint_type' => 'negative',
                ]);
            }
        }
    }
}
