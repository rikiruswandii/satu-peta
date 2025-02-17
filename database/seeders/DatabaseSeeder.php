<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Events\OpdSyncRequested;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder UserSeeder
        $this->call([
            UserSeeder::class,
        ]);

        // Panggil event OpdSyncRequested setelah seeder selesai
        event(new OpdSyncRequested());
    }
}