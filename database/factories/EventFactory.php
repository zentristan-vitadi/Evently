<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 days', '+1 month');
        $endDate = (clone $startDate)->modify('+3 hours');

        return [
            'category_id' => Category::factory(),
            'organizer_id' => User::factory()->panitia(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraphs(3, true),
            'location' => fake()->city().', '.fake()->streetAddress(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'capacity' => fake()->numberBetween(20, 100),
            'status' => 'upcoming',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }

    public function upcoming(): static
    {
        return $this->state(fn () => ['status' => 'upcoming']);
    }

    public function ongoing(): static
    {
        return $this->state(fn () => [
            'status' => 'ongoing',
            'start_date' => now()->subHour(),
            'end_date' => now()->addHours(2),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => 'completed',
            'start_date' => now()->subDays(5),
            'end_date' => now()->subDays(5)->addHours(3),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => 'cancelled']);
    }
}
