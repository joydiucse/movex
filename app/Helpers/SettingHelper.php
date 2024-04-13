<?php

use App\Models\Preference;
use App\Models\Setting;
use App\Models\PackageAndCharge;

if (!function_exists('settingHelper')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function settingHelper($title)
    {
        if ($title == 'package_and_charges'):
            $package_and_charges = PackageAndCharge::get();

            return $package_and_charges;
        elseif ($title == 'preferences'):
            $preferences = Preference::get();
            return $preferences;

       elseif ($title == 'delivery_otp'):
            $otp_preferences = Setting::where('title','delivery_otp')->first();
            return $otp_preferences;
            
        else:
            $data = Setting::where('title', $title)->first();
            if(!blank($data)):
                return $data->value;
            else:
                return '';
            endif;
        endif;
    }
}


