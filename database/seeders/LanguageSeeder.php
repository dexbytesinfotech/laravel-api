<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;
class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::create([
            'name' => 'English',
            'code' => 'en',
            'is_default' => 1, 
            'status' => 1,
        ]);
        Language::create([
            'name' => 'Arebic',
            'code' => 'ar',
            'status' => 1,
        ]);
    }
}
