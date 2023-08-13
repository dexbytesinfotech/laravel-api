<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Posts\Post;
use Faker\Generator as Faker;


class CreatePrivacyPolicyPageSeeder extends Seeder
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
                    'title' => 'Privacy Policy',
                    'content'=> $faker->text(700),
                    'status' => 'published',
                    'post_type' => 'page'
                    ]);
         
    }
}