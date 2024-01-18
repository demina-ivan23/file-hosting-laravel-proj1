<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->count(30)->create();
        \App\Models\User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@doe.com',
            'password' => bcrypt('password')
        ]);
    }
}
