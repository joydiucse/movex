<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hub;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\DeliveryManInterface;
use App\Http\Requests\Admin\DeliveryMan\DeliveryManStoreRequest;
use App\Http\Requests\Admin\DeliveryMan\DeliveryManUpdateRequest;
use App\Models\DeliveryMan;
use App\Models\LogActivity;
use Sentinel;

class DeliveryManController extends Controller
{
    protected $delivery_man;

    public function __construct(DeliveryManInterface $delivery_man)
    {
        $this->delivery_man     = $delivery_man;
    }

    public function index()
    {
        $hubs         = Hub::all();
        $delivery_men = $this->delivery_man->paginate(\Config::get('greenx.paginate'));
        return view('admin.delivery-man.index', compact('delivery_men', 'hubs'));
    }

    public function create()
    {
        $hubs = Hub::all();
        return view('admin.delivery-man.create',compact('hubs'));
    }

    public function store(DeliveryManStoreRequest $request)
    {
        if($this->delivery_man->store($request)):
            return redirect()->route('delivery.man')->with('success', __('created_successfully'));
       else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
       endif;
    }

    public function edit($id)
    {
        $delivery_man = DeliveryMan::with('companyAccount')->find($id);
        if(hasPermission('read_all_delivery_man') || $delivery_man->user->hub_id == Sentinel::getUser()->hub_id || $delivery_man->user->hub_id == ''):
            $hubs = Hub::all();
            return view('admin.delivery-man.edit', compact('delivery_man','hubs'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function update(DeliveryManUpdateRequest $request)
    {
        if($this->delivery_man->update($request)):
            return redirect()->route('delivery.man')->with('success', __('updated_successfully'));
       else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
       endif;

    }

    public function delete($id)
    {
        $delivery_man = $this->delivery_man->get($id);
        if(hasPermission('read_all_delivery_man') || $delivery_man->user->hub_id == Sentinel::getUser()->hub_id || $delivery_man->user->hub_id == ''):
            if($this->delivery_man->delete($id)):
                $success[0] = __('deleted_successfully');
                $success[1] = 'success';
                $success[2] = __('deleted');
                return response()->json($success);
            else:
                $success[0] = __('something_went_wrong_please_try_again');
                $success[1] = 'error';
                $success[2] = __('oops');
                return response()->json($success);
            endif;
        else:
            $success[0] = __('access_denied');
            $success[1] = 'error';
            $success[2] = __('oops');
            return response()->json($success);
        endif;
    }

    public function statusChange(Request $request)
    {
        if($this->delivery_man->statusChange($request['data'])):
            $success = __('updated_successfully');
            return response()->json($success);
        else:
            $success = __('something_went_wrong_please_try_again');
            return response()->json($success);
        endif;

    }

    public function vertualChange(Request $request)
    {
        if($this->delivery_man->vertualChange($request['data'])):
            $success = __('updated_successfully');
            return response()->json($success);
        else:
            $success = __('something_went_wrong_please_try_again');
            return response()->json($success);
        endif;

    }

    public function shuttleChange(Request $request)
    {
        if($this->delivery_man->shuttleChange($request['data'])):
            $success = __('updated_successfully');
            return response()->json($success);
        else:
            $success = __('something_went_wrong_please_try_again');
            return response()->json($success);
        endif;

    }

    public function personalInfo($id)
    {
        $delivery_man = $this->delivery_man->get($id);
        if(hasPermission('read_all_delivery_man') || $delivery_man->user->hub_id == Sentinel::getUser()->hub_id || $delivery_man->user->hub_id == ''):
            return view('admin.delivery-man.details.personal-info', compact('delivery_man'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function accountActivity($id)
    {
        $delivery_man = $this->delivery_man->get($id);
        if(hasPermission('read_all_delivery_man') || $delivery_man->user->hub_id == Sentinel::getUser()->hub_id || $delivery_man->user->hub_id == ''):
            $login_activities = LogActivity::where('user_id', $delivery_man->user_id)->orderBy('id', 'desc')->limit(20)->get();
            return view('admin.delivery-man.details.account-activity', compact('login_activities', 'delivery_man'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function filter(Request $request)
    {
        $hubs = Hub::all();
        $delivery_men = $this->delivery_man->filter($request);
        return view('admin.delivery-man.index', compact('delivery_men', 'hubs'));

    }

    public function statements($id)
    {
        $delivery_man    = $this->delivery_man->get($id);
        $statements      = $delivery_man->accountStatements()->paginate(\Config::get('greenx.paginate'));
        return view('admin.delivery-man.details.statements', compact('statements','delivery_man'));
    }

    public function editLog($id)
    {
        $delivery_man_log    = $this->delivery_man->editLog($id);
        $deliveryMan            = DeliveryMan::find($id);
        $val = 1;
        return view('admin.delivery-man.log-hisotry', compact('delivery_man_log', 'deliveryMan', 'val'));
    }
}
