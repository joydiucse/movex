<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\UserStoreRequest;
use App\Http\Requests\Admin\Users\UserUpdateRequest;
use App\Models\LogActivity;
use App\Repositories\Interfaces\MerchantStaffInterface;
use Illuminate\Http\Request;
use Sentinel;

class MerchantStaffController extends Controller
{
    protected $staffs;

    public function __construct(MerchantStaffInterface $staffs)
    {
        $this->staffs       = $staffs;

    }
    public function index()
    {
        $staffs = $this->staffs->paginate(Sentinel::getUser()->merchant);
        return view('merchant.staffs.index', compact('staffs'));
    }
    public function create()
    {
        return view('merchant.staffs.create');
    }
    public function store(UserStoreRequest $request)
    {
        if($this->staffs->store($request)):
            return redirect()->route('merchant.staffs')->with('success', __('created_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function edit($id)
    {
        $staff = $this->staffs->get($id);
        return view('merchant.staffs.edit', compact('staff'));
    }

    public function update(UserUpdateRequest $request)
    {
        if($this->staffs->update($request)):
            return redirect()->route('merchant.staffs')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }

    public function statusChange(Request $request)
    {
        if($this->staffs->statusChange($request['data'])):
            $success = __('updated_successfully');
            return response()->json($success);
        else:
            $success = __('something_went_wrong_please_try_again');
            return response()->json($success);
        endif;
    }

    public function personalInfo($id)
    {
        $staff = $this->staffs->get($id);
        if($staff->merchant_id == Sentinel::getUser()->merchant->id):
            return view('merchant.staffs.details.personal-info', compact('staff'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;

    }

    public function accountActivity($id)
    {
        $staff         = $this->staffs->get($id);
        if($staff->merchant_id == Sentinel::getUser()->merchant->id):
            $login_activities = LogActivity::where('user_id', $id)->orderBy('id', 'desc')->limit(20)->get();
            return view('merchant.staffs.details.account-activity', compact('login_activities', 'staff'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }
}
