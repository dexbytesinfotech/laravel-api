<?php

namespace Database\Factories\Media;

use Illuminate\Database\Eloquent\Factories\Factory;
use File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $collectionName = $this->faker->randomElement(['store',  'banner']);
        return [
            'user_id' => \App\Models\User::all()->random()->id,
            'collection_name' => $collectionName,
            'name' => $collectionName.'.png',
            'file_name' =>  'dummy/'.$collectionName.'.png',
            'disk' => config('filesystems.default'),
            'size' =>  00,
            'mime_type' => '',
        ];
    }
}
