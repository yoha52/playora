<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Cricket', 'image' => 'cricket.png'],
            ['name' => 'Football', 'image' => 'football.png'],
            ['name' => 'Basketball', 'image' => 'basketball.png'],
            ['name' => 'Volleyball', 'image' => 'volleyball.png'],
            ['name' => 'Tennis', 'image' => 'tennis.png'],
            ['name' => 'Badminton', 'image' => 'badminton.png'],
            ['name' => 'Padel', 'image' => 'padel.png'],
            ['name' => 'Shooting', 'image' => 'shooting.png'],
            ['name' => 'Archery', 'image' => 'archery.png'],
        ];

        $imagePath = public_path('assets/img/court-types');

        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                ['name' => $categoryData['name']],
                ['active' => true]
            );

            $fullImagePath = $imagePath.DIRECTORY_SEPARATOR.$categoryData['image'];
            if (File::exists($fullImagePath) && ! $category->hasMedia('picture')) {
                $category->addMedia($fullImagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('picture');
            }
        }
    }
}
