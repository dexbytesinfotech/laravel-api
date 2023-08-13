<?php

namespace Database\Seeders;

use App\Models\Stores\StoreType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateStoreTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StoreType::create([
                'name' => 'Veg',
                'status' => 1,
                'created_at' => \Carbon\Carbon::now()
        ]);
        StoreType::create([
                'name' => 'Non Veg',
                'status' => 1,
                'created_at' => \Carbon\Carbon::now()
        ]);
    }
}