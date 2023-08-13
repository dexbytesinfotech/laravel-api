<?php

namespace Database\Factories\Stores;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Stores\Store;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stores\Store>
 */
class StoreMetaDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $store = new Store;
        return [
           'key'       => 'business_hours',
            'value'     => $store->getDefaultBusinessHours()
        ];
        
    }
}
