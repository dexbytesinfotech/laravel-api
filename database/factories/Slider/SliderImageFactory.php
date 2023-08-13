<?php

namespace Database\Factories\Slider;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Slider\SliderImage>
 */
class SliderImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'slider_id' =>  \App\Models\Slider\Slider::all()->random()->id,
            'title' => $this->faker->unique()->word(3),
            'image' => \App\Models\Media\Media::all()->where('collection_name', 'banner')->random()->file_name,
            'status' => 1
        ];
    }
}
