<?php

namespace Database\Factories\Stores;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
 

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stores\StoreOwners>
 */
class StoreOwnersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
       
        return [
           'user_id' =>  \App\Models\User::factory()->create()->id
        ];
        
    }
}
