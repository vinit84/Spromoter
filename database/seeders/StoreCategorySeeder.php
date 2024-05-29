<?php

namespace Database\Seeders;

use App\Models\StoreCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add some website categories here
        $categories = [
            'Fashion',
            'Electronics',
            'Home & Garden',
            'Health & Beauty',
            'Toys & Hobbies',
            'Sports & Outdoors',
            'Automotive',
            'Books & Media',
            'Business & Industrial',
            'Food & Beverages',
            'Art & Entertainment',
            'Services',
            'Travel',
            'Other',
        ];

        foreach ($categories as $category) {
            StoreCategory::updateOrCreate([
                'name' => $category,
            ],[
                'name' => $category,
            ]);
        }
    }
}
