<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use Jenssegers\Agent\Facades\Agent;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent(fake()->userAgent());
        return [
            'uuid' => fake()->uuid(),
            'store_id' => 1,
            'user_id' => 2,
            'product_id' => Product::factory(),
            'name' => fake()->name(),
            'email' => fake()->email(),
            'title' => fake()->sentence(),
            'comment' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'source' => fake()->randomElement(['woocommerce', 'shopify', 'magento', 'bigcommerce', 'custom']),
            'collect_from' => fake()->randomElement(['unknown', 'import', 'automatic_review_request', 'widget', 'facebook_tab', 'dedicated_page', 'link_to_preview']),
            'agent' => fake()->userAgent(),
            'device' => [
                'os' => $agent->platform(),
                'os_version' => $agent->version($agent->platform()),
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'device' => $agent->device(),
                'device_type' => $agent->deviceType(),
                'robot' => $agent->robot(),
                'languages' => $agent->languages(),
            ],
            'location' => [
                'ip'           => fake()->ipv4(),
                'iso_code'     => fake()->countryCode(),
                'country'      => fake()->country(),
                'city'         => fake()->city(),
                'state'        => fake()->state(),
                'state_name'   => fake()->state(),
                'postal_code'  => fake()->postcode(),
                'lat'          => fake()->latitude(),
                'lon'          => fake()->longitude(),
                'timezone'     => fake()->timezone(),
                'continent'    => fake()->randomElement(['Africa', 'Antarctica', 'Asia', 'Europe', 'North America', 'Oceania', 'South America']),
                'currency'     => fake()->currencyCode(),
                'default'      => fake()->boolean(),
            ],
            'is_verified' => fake()->boolean(),
            'is_approved' => fake()->boolean(),
            'status' => 'published', //fake()->randomElement(['pending', 'published', 'spam', 'rejected']),
            'created_at' => fake()->dateTimeBetween(today()->subWeeks(2), now()),
        ];
    }
}
