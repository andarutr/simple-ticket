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
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@domain.test',
            'password' => Hash::make('test1234'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Amorim',
            'email' => 'amorim@domain.test',
            'password' => Hash::make('test1234'),
            'role' => 'karyawan'
        ]);
    }
}
