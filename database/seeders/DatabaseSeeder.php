<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

       \Artisan::call('passport:install');
       // settings
        $this->call(ConfigSeeder::class); 
        //Users
        $this->call(CreateAdminUserSeeder::class);
        $this->call(RolesTableSeeder::class);
        //FAQ
        $this->call(FaqCategorySeeder::class);
        //PAGES
        $this->call(CreateAboutUsPageSeeder::class);
        $this->call(CreatePrivacyPolicyPageSeeder::class);
        $this->call(CreateTermOfUsePageSeeder::class);
        //WORDS OR LOCATIONS
        $this->call(CreateLocationSeeder::class);
        //DEFAULT LANG
        $this->call(LanguageSeeder::class);         
        //Tickets 
        $this->call(CreateTicketCategorySeeder::class);
        //Stores
        $this->call(CreateStoreSeeder::class);
        $this->call(CreateStoreTypeSeeder::class);

        //factory run the environment is either local OR staging...
        if (App::environment(['local', 'staging'])) {
            //Dummy Media Files 
            \App\Models\Media\Media::factory(15)->create();
            //Dummy Faq 
            \App\Models\Faq\FaqCategory::factory(5)->create();
            \App\Models\Faq\Faq::factory(15)->create();
            //Dummy Sliders 
            \App\Models\Slider\Slider::factory(1)->create();
            \App\Models\Slider\SliderImage::factory(5)->create();
            //Dummy stores 
            \App\Models\Stores\StoreAddress::factory(50)->create();
        }
    }
}
