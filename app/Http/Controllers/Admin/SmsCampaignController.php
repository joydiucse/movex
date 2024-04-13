<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SmsSendRequest;
use App\Models\DeliveryMan;
use App\Models\Merchant;
use App\Models\User;
use App\Traits\SmsSenderTrait;
use Illuminate\Http\Request;
use SoapClient;
use DB;

class SmsCampaignController extends Controller
{
    use SmsSenderTrait;

    public function sendSms(){
        return view('admin.sms-campaign.send-sms');
    }
    public function sendCustomSms(){
        return view('admin.sms-campaign.custom-sms');
    }
    public function sendSmsPost(SmsSendRequest $request){
        if ($request->sms_to == 'merchant'):
            if ($request->merchant == 'all_merchant'):
                $merchants = Merchant::select('phone_number')->get();
            elseif($request->merchant == 'selected_merchants'):
                $request_array = $request->merchants ?? [];
                $merchants = Merchant::whereIn('id', $request_array)->select('phone_number')->get();
            endif;
            $phone_numbers = $merchants->implode('phone_number', ', ');

        elseif ($request->sms_to == 'delivery_man'):
            if ($request->delivery_man == 'all_delivery_men'):
                $delivery_men = DeliveryMan::select('phone_number')->get();
            elseif($request->delivery_man == 'selected_delivery_men'):
                $request_array = $request->delivery_men ?? [];
                $delivery_men = DeliveryMan::whereIn('id', $request_array)->select('phone_number')->get();
            endif;
            $phone_numbers = $delivery_men->implode('phone_number', ', ');

        elseif ($request->sms_to == 'custom'):
            $phone_numbers = $request->phone_numbers;
        endif;

        if($this->smsBulkSMSByReve('bulk-sms',$phone_numbers, $request->sms_body)):
            return back()->with('success', __('sms_successfully_send'));
        else:
            return back()->with('danger', __('unable_to_send_sms'));
        endif;

    }
}
