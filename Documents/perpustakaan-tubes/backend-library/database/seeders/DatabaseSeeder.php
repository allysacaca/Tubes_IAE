<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User untuk testing
        $user1 = User::create([
            'name' => 'Admin Library',
            'email' => 'admin@library.com',
            'password' => Hash::make('password123'),
        ]);

        $user2 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user3 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 2. Seed Books
        $this->call([
            BookSeeder::class,
        ]);

        // 3. Buat Member dari User yang sudah dibuat (OPTIONAL)
        \App\Models\Member::create([
            'user_id' => $user2->id,
            'member_code' => 'MBR20250001',
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567890',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'join_date' => now(),
            'expiry_date' => now()->addYear(),
            'status' => 'active',
        ]);

        \App\Models\Member::create([
            'user_id' => $user3->id,
            'member_code' => 'MBR20250002',
            'full_name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '081234567891',
            'address' => 'Jl. Contoh No. 456, Jakarta',
            'join_date' => now(),
            'expiry_date' => now()->addYear(),
            'status' => 'active',
        ]);

        $this->command->info('✅ Database seeded successfully!');
    }
}
