<?php

namespace Database\Seeders;

use App\Enums\StoreType;
use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'name' => 'Pusat',
            'type' => StoreType::Main,
        ]);
    }
}
