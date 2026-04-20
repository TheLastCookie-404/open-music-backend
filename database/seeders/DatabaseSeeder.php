<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => env('SUPER_ADMIN_NAME'),
            'email' => env('SUPER_ADMIN_MAIL'),
            'password' => env('SUPER_ADMIN_HASH_SECRET'),
        ]);
    }
}
