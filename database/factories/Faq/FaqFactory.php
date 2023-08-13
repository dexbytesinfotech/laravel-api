<?php

namespace Database\Factories\Faq;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq\Faq>
 */
class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(5),
            'descriptions' => $this->faker->paragraph(6),
            'status' => 1,
            'faq_category_id' =>  \App\Models\Faq\FaqCategory::all()->random()->id,
            'role_type' => $this->faker->randomElement(['driver', 'agent'])
        ];
    }
}
