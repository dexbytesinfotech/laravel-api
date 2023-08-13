<?php

namespace Database\Factories\Stores;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Stores\Store;
use App\Models\Stores\StoreMetaData;
use App\Models\Stores\StoreOwners;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stores\StoreAddress>
 */
class StoreAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $latitude = config('general.latitude_default');
        $longitude = config('general.longitude_default');

        $storeId = \App\Models\Stores\Store::factory()->create()->id;

        \App\Models\Stores\StoreMetaData::factory()->create([
            'store_id'  => $storeId
        ]);


        $storeModel = new Store;
        $defaultBusinessHours =  collect(json_decode($storeModel->getDefaultBusinessHours(),true))->map(function ($value,$key) use($storeId)
        {
            $value['store_id'] = $storeId;
            $value['created_at'] = \Carbon\Carbon::now();
            return $value;
        })->all();


        \App\Models\Stores\BusinessHour::insert($defaultBusinessHours);

        \App\Models\Stores\StoreOwners::factory()->create([
            'store_id'  => $storeId
        ]);

        return [
            'store_id'          => $storeId,
            'address_line_1'    => $this->faker->address(),
            'address_line_2'    => $this->faker->streetName(1),
            'address_line_3'    => $this->faker->streetAddress(),
            'landmark'          =>  $this->faker->city(),
            'country'           => "India",
            'zip_post_code'     => $this->faker->postcode(),
            'is_primary'        => 1,
            'addrees_type'      => 'default',
            'latitude'          =>  $this->faker->latitude($min = ($latitude * 10000 - rand(0, 50)) / 10000,
                                    $max = ($latitude * 10000 + rand(0, 50)) / 10000),
            'longitude'         =>  $this->faker->longitude($min = ($longitude * 10000 - rand(0, 50)) / 10000,
                                    $max = ($longitude * 10000 + rand(0, 50)) / 10000
            ),
        ];




    }
}
