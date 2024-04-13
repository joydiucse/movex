<?php

namespace App\Http\Controllers\Api\DeliveryMan\V13;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
use JWTAuth;
use App\Traits\ApiReturnFormatTrait;
use App\Models\DeliveryMan;
class ConfigController extends Controller
{
    use ApiReturnFormatTrait;
    public function configaration(Request $request)
    {

        $current_version = Setting::where('title', 'current_version')->select('title', 'value')->first();
        $update_skipable = Setting::where('title',  'update_skipable')->select('title', 'value')->first();
        $update_url = Setting::where('title',  'update_url')->select('title', 'value')->first();
        $delivery_otp = Setting::where('title',  'delivery_otp')->select('title', 'value')->first();

        $domain_info  = Setting::where('title','sip_domain')->first();
        $phone_visible  = Setting::where('title','phone_visible')->first();

        $setting_info = [
            'current_version' => $current_version->value,
            'update_skipable' => $update_skipable->value,
            'update_url'      =>  $update_url->value
        ];

        $delivery_info = [
                    'sip_domain' => $domain_info->value,
                    'user_id' => '',
                    'password' => '',
                    'phone_visible' =>$phone_visible->value,
        ];
        $response['delivery_otp'] =  $delivery_otp->value;
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

    public function getConfiguration(Request $request)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return $this->responseWithError(__('unauthorized_user'), '' , 404);
        }

        $current_version = Setting::where('title', 'current_version')->select('title', 'value')->first();
        $update_skipable = Setting::where('title',  'update_skipable')->select('title', 'value')->first();
        $update_url = Setting::where('title',  'update_url')->select('title', 'value')->first();
        $domain_info  = Setting::where('title','sip_domain')->first();
        $phone_visible  = Setting::where('title','phone_visible')->first();
        $delivery_otp = Setting::where('title',  'delivery_otp')->select('title', 'value')->first();

        $setting_info = [
            'current_version' => $current_version->value,
            'update_skipable' => $update_skipable->value,
            'update_url' => $update_url->value,
        ];

        $delivery_info = [
                    'sip_domain' => $domain_info->value,
                    'user_id' => ($user->deliveryMan->sip_extension != '' or $user->deliveryMan->sip_extension !=Null) ? $user->deliveryMan->sip_extension : '',
                    'password' => ($user->deliveryMan->sip_password !='' or $user->deliveryMan->sip_password !=null)? $user->deliveryMan->sip_password : '',
                    'phone_visible' => $phone_visible->value,
                    'dialer_enable' => ($user->deliveryMan->dial_enable == 1) ? "true" : "false",
        ];
        $response['delivery_otp'] =  $delivery_otp->value;
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
