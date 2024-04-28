<?php

use App\Models\Preference;
use App\Models\Setting;
use App\Models\PackageAndCharge;

function getAssetVersion()
{
    return 'v1.01';
}

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

function marchantCanEditInStatus($section='')
{
    if($section=='percel'){
        return ['pending', 'pickup-assigned', 're-schedule-pickup', 'received-by-pickup-man', 'received', 'transferred-to-hub', 'transferred-received-by-hub', 'delivery-assigned', 're-schedule-delivery', 'today-attempt', 'returned-to-greenx', 'return-assigned-to-merchant'];
    }
}
