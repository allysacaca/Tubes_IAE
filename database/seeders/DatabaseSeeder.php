<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book; // <--- KAMU KURANG BARIS INI
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User MEMBER
        User::factory()->create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'member',
        ]);

        // 2. Buat User ADMIN
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('1234567890'),
            'role' => 'admin',
        ]);

        // 3. Buat 10 Buku Dummy
        // Pastikan AuthorFactory juga sudah ada, karena BookFactory memanggil Author
        Book::factory(10)->create();
    }
}