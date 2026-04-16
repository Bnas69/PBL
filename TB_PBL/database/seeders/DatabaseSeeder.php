<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Jalankan semua seeder untuk membuat data awal.
        $this->call([
            ProductSeeder::class,
            InventorySeeder::class,
            SaleSeeder::class,
            ReorderDraftSeeder::class,
        ]);
    }
}
