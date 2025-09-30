<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // pastikan ada user
        $user = User::latest()->first() ?? User::factory()->create();

        // bikin 20 product untuk user yang sama
        Product::factory()->count(1000)->create([
            'user_id' => $user->id,
        ]);
    }
}
