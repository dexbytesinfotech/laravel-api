<?php

namespace Database\Seeders;

use App\Models\Worlds\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Worlds\State;
use App\Models\Worlds\Cities;

class CreateLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = Country::create([
            'name' => config('general.default_country_name'),
            'country_ios_code'  => config('general.default_country_iso_code'),
            'nationality'  => config('general.default_nationality'),
            'is_default'  => 1,
            'status' => 1,
            'country_code' => config('general.default_country_code')
        ]);

        $status = state::create([
            'name'         => config('general.default_state'),
            'country_id'  => $country->id,
            'is_default'   => 1,
            'status' => 1,
        ]);


        Cities::create([
            'name' => config('general.default_city'),
            'country_id'  => $country->id,
            'state_id'  => $status->id,
            'is_default'   => 1,
            'status' =>  1,
        ]);

    }
}
