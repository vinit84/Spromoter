<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'name' => 'English',
                'code' => 'en',
                'is_rtl' => 0,
                'is_system' => 1,
                'is_active' => 1,
            ]
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
