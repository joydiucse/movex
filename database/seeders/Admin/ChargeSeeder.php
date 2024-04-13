<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use App\Models\Charge;

class ChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $attributes = [
            '1'             => ['1', 90, 55, 90, 120],
        	'2'             => ['2', 110, 70, 105, 150],
        	'3'             => ['3', 130, 85, 120, 180],
        	'4'             => ['4', 150, 100, 135, 210],
        	'5'             => ['5', 170, 115, 150, 240],
        	'6'             => ['6', 190, 130, 165, 270],
        	'7'             => ['7', 210, 145, 180, 300],
        	'8'             => ['8', 230, 160, 195, 330],
        	'9'             => ['9', 250, 175, 210, 360],
        	'10'            => ['10', 270, 190, 225, 390]
        ];

        foreach($attributes as $key => $attribute){
        	$cod_charge                       = new Charge();
        	$cod_charge->weight               = $attribute[0];
            $cod_charge->same_day             = $attribute[1];
            $cod_charge->next_day             = $attribute[2];
            // $cod_charge->frozen               = $attribute[3];
            $cod_charge->sub_city             = $attribute[3];
            $cod_charge->outside_dhaka        = $attribute[4];
            // $cod_charge->third_party_booking    = $attribute[6];
        	$cod_charge->save();
        }
    }
}
