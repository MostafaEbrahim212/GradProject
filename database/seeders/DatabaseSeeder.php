<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Admin;
use App\Models\Fundraisers;
use App\Models\FundraisersCategories;
use Database\Factories\FundraiserCategoryFactory;
use Database\Factories\FundraiserFactory;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            AdminSeeder::class,
            UserSeeder::class
        ]);
        // FundraisersCategories::factory(10)->create()->each(function ($category) {
        //     FundraisersCategories::factory(5)->create([
        //         'parent_id' => $category->id,
        //     ]);
        // });
        // Fundraisers::factory(10)->create()->each(function ($fundraiser) {
        //     $fundraiser->categories()->attach(
        //         FundraisersCategories::inRandomOrder()->first()->id
        //     );
        // });
    }
}
