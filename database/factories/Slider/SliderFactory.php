<?php

namespace Database\Factories\Slider;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Slider\Slider>
 */
class SliderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' =>  $this->faker->unique()->word(3),
            'code' =>  $this->faker->unique()->word(3),
            'description' => $this->faker->paragraph(1),
            'status' => 1,
            'is_default' => 1,
            'start_date_time' => Carbon::now(),
            'end_date_time' => Carbon::now()->addMonths(6)
        ];
    }
}
