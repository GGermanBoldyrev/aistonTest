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

        return [
            'number' => sprintf('KC-%04d', $counter++),
            'pharmacy_id' => Pharmacy::inRandomOrder()->first()?->id ?? Pharmacy::factory(),
            'topic' => $this->faker->sentence(4),
            'priority_id' => Priority::inRandomOrder()->first()?->id,
            'category_id' => Category::inRandomOrder()->first()?->id ?? null,
            'technician_id' => Technician::inRandomOrder()->first()?->id ?? Technician::factory(),
            'status_id' => Status::inRandomOrder()->first()?->id ?? 1,
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'updated_at' => now(),
        ];
    }
}
