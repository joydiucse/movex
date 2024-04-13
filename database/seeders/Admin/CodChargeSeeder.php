<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use App\Models\CodCharge;

class CodChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributes = [
            '1'           => ['dhaka', 0.0],
        	'2'           => ['sub_city', 1.0],
        	'3'           => ['outside_dhaka', 1.0],
        	// '4'           => ['third_party_booking', 1.0]
        ];

        foreach($attributes as $key => $attribute){
        	$permission            = new CodCharge();
        	$permission->location  = $attribute[0];
        	$permission->charge    = $attribute[1];
        	$permission->save();
        }
    }
}

