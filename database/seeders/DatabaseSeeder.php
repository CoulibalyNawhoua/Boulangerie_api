<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Ambeu',
            'first_name' => 'Ambeu',
            'last_name' => 'Aka Anderson',
            'email' => 'andersonambeu@gmail.com',
            'phone' => '0768121340',
            'password' => '123456'
        ]);
    }
}
