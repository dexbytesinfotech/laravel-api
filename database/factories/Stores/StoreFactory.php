<?php

namespace Database\Factories\Stores;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stores\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'          => $this->faker->company(),
            'descriptions'  =>  $this->faker->realText($maxNbChars = 200, $indexSize = 2),
            'phone'         => config('general.default_country_code').$this->faker->unique()->numerify('##########'),
            'email'         => $this->faker->unique()->safeEmail(),
            'content'       => $this->faker->paragraph(1),
            'store_type' =>  $this->faker->randomElement(['Veg', 'Non Veg']),
            'country_code'  => config('general.default_country_code'),
            'logo_path'     => \App\Models\Media\Media::all()->where('collection_name', 'store')->random()->file_name,
            'status'        => 1,
            'is_primary'        => 0,
            'is_open'  => 1,
            'number_of_branch'      => 1,
            'is_searchable' => 1,
            'background_image_path' => '',
            'application_status' => 'approved'
        ];

    }
}
