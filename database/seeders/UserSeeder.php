<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        for ($i = 1; $i < 6; $i++) {
            User::factory()->create([
                'name' => "user$i",
                'email' => "user$i@example.com",
                'password' => Hash::make('123456789'),
            ]);
        }
    }
}
