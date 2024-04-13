<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingUpdateRequest;
use App\Models\PackageAndCharge;
use App\Models\Parcel;
use App\Models\Preference;
use App\Repositories\Interfaces\Admin\SettingInterface;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $setting;
    public function __construct(SettingInterface $setting)
    {
        $this->setting    = $setting;

    }
    public function pagination()
    {
        return view('admin.settings.pagination');
    }
    public function charges()
    {
        return view('admin.settings.charges');
    }
    public function timeAndDays()
    {
        return view('admin.settings.time_and_days');
    }
    public function sms()
    {
        return view('admin.settings.sms-settings');
    }
    public function preference()
    {
        return view('admin.settings.preference-settings');
    }
    public function packingCharge()
    {
        $packaging_and_charges = $this->setting->packingCharge();
        if($packaging_and_charges != false):
            return view('admin.settings.packing-charges', compact('packaging_and_charges'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }
    public function packingChargeAdd()
    {
        $view = view('admin.settings.packaging_charge_new_row')->render();
        return response()->json(['view' => $view]);
    }

    public function databaseBackupSetting()
    {
        return view('admin.settings.database-backup');
    }
    public function store(SettingUpdateRequest $request)
    {
        if($this->setting->store($request)):
            return back()->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function mobileAppSetting()
    {
        return view('admin.settings.mobile-app-setting');
    }

    public function packagingChargeUpdate(Request $request)
    {
        if($this->setting->packagingChargeUpdate($request)):
            return back()->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function deletePackagingCharge(Request $request, $id)
    {
        $parcels = Parcel::where('packaging', $id)->first();

        if (!blank($parcels)):
            $data['success']    = false;
            $data['message']    = __('this_packaging_already_got_used');
            return response()->json($data);
        endif;

        if($this->setting->deletePackagingCharge($id)):
            $data['success']     = true;
            $data['message']     = __('deleted_successfully');
            return response()->json($data);
        else:
            $error = __('something_went_wrong_please_try_again');
            return response()->json($error);
        endif;
    }

    public function statusChange(Request $request){
        try{

            $sms_template = Preference::find($request['data']['id']);
            $sms_template[$request['data']['change_for']] = $request['data']['status'];
            $sms_template->save();

            $success = __('updated_successfully');
            return response()->json($success);
        }
        catch (\Exception $e){
            $success = __('something_went_wrong_please_try_again');
            return response()->json($success);
        }

    }

    public function deliveryOtpPermission(Request $request)
    {
         $request->changePermission;
         if(hasPermission('preference_setting_update'))
         {
             $this->setting->deliveryOtpPermission($request->changePermission);
             $success = __('permission_successfully_changed');
             return response()->json($success);
         }else{
            $success = __('something_went_wrong_please_try_again');
            return response()->json($success);
         }

    }

    public function sip_domain()
    {
        return view("admin.settings.sip_domain");
    }

    public function appInfo()
    {
        return view("admin.settings.app_info");
    }
}
