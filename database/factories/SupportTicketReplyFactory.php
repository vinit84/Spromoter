<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupportTicketReply>
 */
class SupportTicketReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = fake()->randomElement([1, 6]);
        return [
            'support_ticket_id' => 1,
            'user_id' => $user,
            'message' => $this->faker->sentence,
            'is_customer' => $user !== 1,
        ];
    }
}
