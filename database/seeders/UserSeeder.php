<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->has(Account::factory()->count(3))->create([
            'id' => 1,
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
