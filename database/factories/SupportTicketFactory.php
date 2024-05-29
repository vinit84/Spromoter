<?php

namespace Database\Factories;

use App\Models\SupportTicket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupportTicket>
 */
class SupportTicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'category' => $this->faker->randomElement(['billing', 'technical', 'sales']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'department' => $this->faker->randomElement(['general', 'sales', 'support']),
            'status' => $this->faker->randomElement(['open', 'closed']),
        ];
    }
}
