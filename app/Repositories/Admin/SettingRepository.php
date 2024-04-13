<?php

namespace App\Repositories\Admin;

use App\Models\PackageAndCharge;
use App\Models\Setting;
use App\Repositories\Interfaces\Admin\SettingInterface;
use DB;
use App\Models\CustomerParcelSmsTemplates;
class SettingRepository implements SettingInterface {

    public function store($request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->except('_token') as $key => $value) :
                $setting = Setting::where('title', $key)->first();
                if ($setting == "" || $setting == null ):
                    $setting        = new Setting();
                    $setting->title = $key;
                    $setting->value = $value;
                else :
                    $setting->value = $value;
                endif;
                $setting->save();
            endforeach;

            DB::commit();
            return true;
        } catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function arrayStore($request)
    {
        DB::beginTransaction();
        try {
            $array = [] ;
            $title = '';

            if (isset($request->packaging_types)):
                $title = 'package_types_and_charge';
                foreach ($request->packaging_types as $key=> $type):
                    if (!blank($type) && !blank($request->charges[$key])):
                        $array+= [$type => $request->charges[$key]];
                    endif;
                endforeach;
            endif;

            $setting = Setting::where('title', $title)->first();

            if ($title != ''):
                if ($setting == "" || $setting == null) :
                    $setting        = new Setting();
                    $setting->title = $title;
                    $setting->array_value = $array;
                else :
                    $setting->array_value = $array;
                endif;

                $setting->save();
            endif;

            DB::commit();
            return true;
        } catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function packingCharge()
    {
        try {
            $package_and_charges = PackageAndCharge::all();
            return $package_and_charges;
        } catch (\Exception $e){
            return false;
        }
    }

    public function deletePackagingCharge($id)
    {
        DB::beginTransaction();
        try{
            PackageAndCharge::find($id)->delete();
            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function packingChargeAdd()
    {
        DB::beginTransaction();
        try {
            $package_charge = new PackageAndCharge();
            $package_charge->save();
            DB::commit();
            return $package_charge->id;
        } catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function packagingChargeUpdate($request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->packaging_types as $key => $type):
                $old_record = PackageAndCharge::where('package_type',$type)->where('id','!=',$request->ids[$key])->first();
                if (blank($old_record) && $type != ''):
                    $table = PackageAndCharge::find($request->ids[$key]);
                    if (blank($table)):
                        $table = new PackageAndCharge();
                    endif;
                    $table->package_type =  $type;
                    $table->charge       = $request->charges[$key];
                    $table->save();
                elseif (($type == '' || $old_record) && $request->ids[$key] != ''):
                    $this->deletePackagingCharge($request->ids[$key] );
                endif;
            endforeach;
            DB::commit();
            return true;
        } catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }


    public function deliveryOtpPermission($permission)
    {
        DB::beginTransaction();
        try{

            $delivery_otp = Setting::where('title','delivery_otp')->first();
            if(!blank($delivery_otp)):

                if($permission == 'all'):
                    $all_parcel = CustomerParcelSmsTemplates::where('subject', "customer_parcel_delivery_otp_event")->first();
                    $all_parcel->sms_to_customer = 1;
                    $all_parcel->save();
                   
                elseif($permission == 'conditional'):
                    $cod_parcel = CustomerParcelSmsTemplates::where('subject', "customer_parcel_delivery_otp_cod_event")->first();
                    $cod_parcel->sms_to_customer = 1;
                    $cod_parcel->save();

                    $all_parcel_event = CustomerParcelSmsTemplates::where('subject', "customer_parcel_delivery_otp_event")->first();
                    $all_parcel_event->sms_to_customer = 0;
                    $all_parcel_event->save();
                
                else:
                    $all_parcel = CustomerParcelSmsTemplates::whereIn('subject', ["customer_parcel_delivery_otp_event", "customer_parcel_delivery_otp_cod_event"])
                    ->update(['sms_to_customer' => 0]);
                
                endif;

                $delivery_otp->value = $permission;
                $delivery_otp->save();
                DB::commit();
                return true;
            else:
                return false;
            endif;
           
        }catch(\Exception $e){
            DB::rollback();
            return false;
        }
       
    }
}
