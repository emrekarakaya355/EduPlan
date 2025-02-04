<?php

namespace Database\Seeders;

use App\Models\Birim;
use App\Models\Bolum;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Birim::create([
            'id' => 999999999999999,
            'name' => 'Eski Birimlere Açılmış Bölümler'
        ]);
        Bolum::create([
            'id' => 999999999999999,
            'name' => 'Eski Birimlere açılmış bölümler',
            'birim_id' => 999999999999999
        ]);
        // User::factory(10)->create();
/*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

    }
}
