<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Plumbing', 'icon' => '🔧', 'description' => 'Pipe repair, faucet installation, water heater services'],
            ['name' => 'Electrical', 'icon' => '💡', 'description' => 'Wiring, lighting installation, electrical repairs'],
            ['name' => 'Cleaning', 'icon' => '🧹', 'description' => 'House cleaning, office cleaning, deep cleaning'],
            ['name' => 'Tutoring', 'icon' => '📚', 'description' => 'Math, English, Science, and other subjects'],
            ['name' => 'Photography', 'icon' => '📸', 'description' => 'Event photography, portrait, product photography'],
            ['name' => 'Carpentry', 'icon' => '🪚', 'description' => 'Furniture making, repairs, custom woodwork'],
            ['name' => 'Painting', 'icon' => '🎨', 'description' => 'Interior painting, exterior painting, wall art'],
            ['name' => 'Gardening', 'icon' => '🌱', 'description' => 'Lawn care, landscaping, garden maintenance'],
            ['name' => 'IT Support', 'icon' => '💻', 'description' => 'Computer repair, network setup, tech support'],
            ['name' => 'Event Planning', 'icon' => '🎉', 'description' => 'Wedding planning, party organizing, events'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon'],
                'description' => $category['description'],
            ]);
        }
    }
}