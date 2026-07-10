<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\Court;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        $startHour = fake()->numberBetween(8, 20);
        $duration = fake()->numberBetween(1, 3);
        $endHour = min($startHour + $duration, 22);
        $ratePerHour = fake()->numberBetween(50, 200);
        $totalAmount = ($endHour - $startHour) * $ratePerHour;

        return [
            'court_id' => Court::factory(),
            'user_id' => User::factory(),
            'date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'start_time' => sprintf('%02d:00', $startHour),
            'end_time' => sprintf('%02d:00', $endHour),
            'paid_amount' => fake()->randomFloat(2, 0, $totalAmount),
            'total_amount' => $totalAmount,
            'status' => fake()->randomElement(BookingStatus::cases()),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::Confirmed,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::Completed,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::Cancelled,
        ]);
    }

    public function fullyPaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'paid_amount' => $attributes['total_amount'],
        ]);
    }

    public function withDue(): static
    {
        return $this->state(fn (array $attributes) => [
            'paid_amount' => $attributes['total_amount'] * 0.5,
        ]);
    }
}
