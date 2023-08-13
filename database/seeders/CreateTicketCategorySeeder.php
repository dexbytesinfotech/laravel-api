<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tickets\TicketCategory;


class CreateTicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TicketCategory::create(['name' => 'uncategorized', 'is_default'=> 1]);
        TicketCategory::create(['name' => 'update-mobile-number', 'is_default'=> 1]);    
    
    }
}