<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Pharmacy;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $counter = 1;
        $createdAt = $this->faker->dateTimeBetween('-2 months', 'now');

        return [
            // Обязательные поля
            'number' => sprintf('KC-%04d', $counter++),
            'topic' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3),
            'is_warranty_case' => $this->faker->boolean(25),
            'user_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),

            // Обязательные связи
            'pharmacy_id' => Pharmacy::factory(),
            'priority_id' => Priority::factory(),
            'category_id' => Category::factory(),
            'status_id' => Status::factory(),

            // Необязательные поля
            'technician_id' => null,
            'reacted_at' => null,
            'resolved_at' => null,

            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }

    /**
     * Для сидера - использует существующие сущности
     */
    public function forSeeder(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority_id' => Priority::inRandomOrder()->first()?->id ?? Priority::factory(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'status_id' => Status::inRandomOrder()->first()?->id ?? Status::factory(),
            'technician_id' => Technician::inRandomOrder()->first()?->id,
        ]);
    }
}
