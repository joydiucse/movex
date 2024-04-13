<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
class ConfigController extends Controller
{
    
    public function configaration(Request $request)
    {

        $setting_info = Setting::whereIn('title', ['current_version', 'update_skipable', 'update_url'])->select('title', 'value')->get();
        $domain_info  = Setting::where('title','sip_domain')->first();
        $phone_visible  = Setting::where('title','phone_visible')->first();

        $delivery_info = [
                    'sip_domain' => $domain_info->value,
                    'user_id' => '',
                    'password' => '',
                    'phone_visible' => $phone_visible->value
        ];

        $response['android_info'] = $setting_info;
        $response['sip_config'] = $delivery_info;
       
        if(count($response) > 0):
        return response()->json([
            'success'   => true,
            'message'   => "successfully data found",
            'data'      => $response,
        ],200);
        else:
            return response()->json([
                'success'   => false,
                'message'   => "Sorry data not fund",
                'data'      => [],
            ],400);
        endif;
          
    }





}
