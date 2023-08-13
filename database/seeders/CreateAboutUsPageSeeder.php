<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Posts\Post;
use Faker\Generator as Faker;


class CreateAboutUsPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {   
        Post::create([
                    'user_id' => 1,
                    'title' => 'about us',
                    'content'=> $faker->text(700),
                    'status' => 'published',
                    'post_type' => 'page'
                    ]);
         
    }
}