<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stores\Store;
use App\Models\Stores\StoreAddress;
use App\Models\Stores\BusinessHour;

class CreateStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = Store::create([
            'name'          => 'Store',
            'descriptions'  => 'A short description about Store',
            'phone'         => '0000000000',
            'email'         =>'store@store.com',
            'content'       => 'Explain what your store do.',
            'country_code'  => config('general.default_country_code'),
            'logo_path'     =>'',
            'status'        => 0,
            'is_primary'        => 1,
            'number_of_branch'      => 1,
            'background_image_path' => '',
        ]);

        $address = StoreAddress::create([
            'store_id'          => $store->id,
            'address_line_1'    => 'Dummy Adress',
            'address_line_2'    => 'Dummy Adress line 1',
            'address_line_3'    => 'Dummy Adress line 1',
            'landmark'          => 'Dummy Landmark',
            'city'              => 'Dummy Citty',
            'state'             => 'Dummy state',
            'country'           => 'Dummy country',
            'zip_post_code'     => '000000',
            'is_primary'        => 1,
            'addrees_type'      => 'default',
            'latitude'          => 00000000,
            'longitude'         => 00000000,
        ]);

        $storeModel = new Store;
        $defaultBusinessHours =  collect(json_decode($storeModel->getDefaultBusinessHours(),true))->map(function ($value,$key) use($store)
        {
            $value['store_id'] = $store->id;
            $value['created_at'] = \Carbon\Carbon::now();
            return $value;
        })->all();


        BusinessHour::insert($defaultBusinessHours);
    }
}