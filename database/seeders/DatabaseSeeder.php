<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User one',
            'email' => 'testone@example.com',
            'password' => Hash::make('123456')
        ]);

        User::factory()->create([
            'name' => 'Test User two',
            'email' => 'testtwo@example.com',
            'password' => Hash::make('123456')
        ]);

        User::factory()->create([
            'name' => 'Test User three',
            'email' => 'testthree@example.com',
            'password' => Hash::make('123456')
        ]);
    }
}
