<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerParcelSmsTemplates;
use App\Models\SmsTemplate;
use App\Models\WithdrawSmsTemplate;
use App\Models\NameSmsTemplate;
use Database\Seeders\CustomerParcelSmsSeeder;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    public function smsPreference()
    {
        $sms_templates = SmsTemplate::all();
        $customer_sms_templates = CustomerParcelSmsTemplates::whereNotIn('subject', ['customer_parcel_delivery_otp_event', 'customer_parcel_delivery_otp_cod_event'])->get();
        $withdraw_sms_templates = WithdrawSmsTemplate::all();
        $name_sms_template  = NameSmsTemplate::all();
        $otp_sms_preference = CustomerParcelSmsTemplates::whereIn('subject', ['customer_parcel_delivery_otp_event', 'customer_parcel_delivery_otp_cod_event'])->get();
        return view('admin.preference.sms', compact('sms_templates','customer_sms_templates','withdraw_sms_templates', 'name_sms_template', 'otp_sms_preference'));
    }

    public function statusChange(Request $request){
        try{
            if($request['data']['change_for'] == 'sms_to_merchant'):
                $sms_template = SmsTemplate::find($request['data']['id']);
                $sms_template->sms_to_merchant = $request['data']['status'];
                $sms_template->save();
            elseif($request['data']['change_for'] == 'sms_to_customer'):
                $customer_sms_template = CustomerParcelSmsTemplates::find($request['data']['id']);
                $customer_sms_template->sms_to_customer = $request['data']['status'];
                $customer_sms_template->save();
            elseif($request['data']['change_for'] == 'withdraw_sms'):
                $withdraw_sms_template = WithdrawSmsTemplate::find($request['data']['id']);
                $withdraw_sms_template->sms_to_merchant = $request['data']['status'];
                $withdraw_sms_template->save();
            elseif($request['data']['change_for'] == 'name_sms'):
                $name_sms_template = NameSmsTemplate::find($request['data']['id']);
                $name_sms_template->sms_to_merchant = $request['data']['status'];
                $name_sms_template->save();
            endif;


            $success = __('updated_successfully');
            return response()->json($success);
        }
        catch (\Exception $e){
            $success = __('something_went_wrong_please_try_again');
            return response()->json($request['data']['id']);
        }

    }

    public function maskingStatusChange(Request $request){
        try{
            if($request['data']['change_for'] == 'sms_to_merchant'):
                $sms_template = SmsTemplate::find($request['data']['id']);
                $sms_template->masking = $request['data']['status'];
                $sms_template->save();
            elseif($request['data']['change_for'] == 'sms_to_customer'):
                $customer_sms_template = CustomerParcelSmsTemplates::find($request['data']['id']);
                $customer_sms_template->masking = $request['data']['status'];
                $customer_sms_template->save();
            elseif($request['data']['change_for'] == 'withdraw_sms'):
                $withdraw_sms_template = WithdrawSmsTemplate::find($request['data']['id']);
                $withdraw_sms_template->masking = $request['data']['status'];
                $withdraw_sms_template->save();

            elseif($request['data']['change_for'] == 'name_sms'):
                $name_sms_template = NameSmsTemplate::find($request['data']['id']);
                $name_sms_template->masking = $request['data']['status'];
                $name_sms_template->save();
            endif;

            $success = __('updated_successfully');
            return response()->json($success);
        }
        catch (\Exception $e){
            $success = __('something_went_wrong_please_try_again');
            return response()->json($request['data']['id']);
        }

    }
}
