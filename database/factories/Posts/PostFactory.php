<?php

namespace Database\Factories\Posts;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Posts\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title =  $this->faker->sentence(2);
        return [
            'user_id' => \App\Models\User::all()->random()->id,
            'title' => rtrim($title, '.'),
            'slug' =>   $title,
            'content' => $this->faker->paragraph(1),
            'status' =>  $this->faker->randomElement(['published', 'unpublished']),
            'post_type' => $this->faker->randomElement(['page']),
        ];
    }
}
