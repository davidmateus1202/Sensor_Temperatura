<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin2',
            'email' => 'admin3@gmail.com',
            'password' => bcrypt('admin'),
        ]);
    }
}
