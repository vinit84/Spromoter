<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'group' => fake()->randomElement(['customer', 'admin']),
            'profile_photo_url' => fake()->imageUrl(),
            'company' => fake()->company(),
            'position' => fake()->jobTitle(),
            'address' => fake()->address(),
            'state' => fake()->streetAddress(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'about' => fake()->text(),
            'social_facebook' => fake()->url(),
            'social_twitter' => fake()->url(),
            'social_linkedin' => fake()->url(),
            'social_instagram' => fake()->url(),
            'social_skype' => fake()->url(),
            'status' => fake()->randomElement(['active', 'suspend']),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'deleted_at' => fake()->randomElement([now(), null]),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
