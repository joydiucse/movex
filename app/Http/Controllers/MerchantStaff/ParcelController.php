<?php

namespace App\Http\Controllers\MerchantStaff;

use App\Exports\FilteredParcel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Parcel\ParcelStoreRequest;
use App\Http\Requests\Admin\Parcel\ParcelUpdateRequest;
use App\Models\Charge;
use App\Models\CodCharge;
use App\Models\Parcel;
use App\Repositories\Interfaces\ParcelInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Sentinel;

class ParcelController extends Controller
{
    protected $parcels;

    public function __construct(ParcelInterface $parcels)
    {
        $this->parcels = $parcels;
    }

    public function index(Request $request)
    {
        $charges        = Charge::all();
        $cod_charges    = CodCharge::all();
        $pn             = isset($request->pn) ? $request->pn : '';
        $parcels        = $this->paginateForMerchant(\Config::get('greenx.parcel_merchant_paginate'),$pn);
        return view('merchant.parcel.index', compact('parcels','cod_charges','charges'));
    }

    public function paginateForMerchant($limit,$pn = '')
    {
        return Parcel::where('merchant_id', Sentinel::getUser()->merchant_id)
            ->when(!hasPermission('all_parcel'), function ($query){
                $query->where('user_id',Sentinel::getUser()->id);
            })->where('parcel_no','like','%' . $pn .'%')
            ->orderBy('id', 'desc')->paginate($limit);
    }

    public function create()
    {
        if (@settingHelper('preferences')->where('title','create_parcel')->first()->merchant):
            $shops          = Sentinel::getUser()->staffMerchant->shops->whereIn('id',Sentinel::getUser()->shops);
            $charges        = Charge::all();
            $cod_charges    = CodCharge::all();
            return view('merchant.parcel.create', compact('charges', 'cod_charges','shops'));
        else:
            return back()->with('danger', __('service_unavailable'));
        endif;
    }

    public function store(ParcelStoreRequest $request)
    {
        if (@settingHelper('preferences')->where('title','create_parcel')->first()->merchant):
            if($this->parcels->store($request)):
                return redirect()->route('merchant.staff.parcel')->with('success', __('created_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return redirect()->route('merchant.staff.parcel')->with('danger', __('service_unavailable'));
        endif;

    }

    public function duplicate($id){
        try {
            if (@settingHelper('preferences')->where('title','create_parcel')->first()->merchant):
                $parcel= Parcel::where('parcel_no',$id)->first();
                $shops = Sentinel::getUser()->staffMerchant->shops->whereIn('id',Sentinel::getUser()->shops);
                $default_shop = Sentinel::getUser()->staffMerchant->shops()->where('default',1)->first();
                $charges = Charge::all();
                $cod_charges = CodCharge::all();
                return view('merchant.parcel.create', compact('parcel', 'charges', 'cod_charges','shops','default_shop'));
            else:
                return back()->with('danger', __('service_unavailable'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }
    public function detail($id){
        try {
            $parcel = Parcel::with('merchant.user','events','hub')->where('parcel_no',$id)->first();
            if(($parcel->merchant->id == Sentinel::getUser()->merchant_id && hasPermission('all_parcel')) || ($parcel->merchant->id == Sentinel::getUser()->merchant_id && $parcel->user_id == Sentinel::getUser()->id)):
                $charges        = Charge::all();
                $cod_charges    = CodCharge::all();
                return view('merchant.parcel.detail', compact('parcel','cod_charges','charges'));
            else:
                return back()->with('danger', __('access_denied'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }
    public function print($id){
        try {
            $parcel= Parcel::where('parcel_no',$id)->first();
            if(($parcel->merchant->id == Sentinel::getUser()->merchant_id && hasPermission('all_parcel')) || ($parcel->merchant->id == Sentinel::getUser()->merchant_id && $parcel->user_id == Sentinel::getUser()->id)):
                $charges        = Charge::all();
                $cod_charges    = CodCharge::all();
                return view('merchant.parcel.print', compact('parcel','cod_charges','charges'));
            else:
                return back()->with('danger', __('access_denied'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function edit($id)
    {
        $shops          = Sentinel::getUser()->staffMerchant->shops->whereIn('id',Sentinel::getUser()->shops);
        $parcel= Parcel::where('parcel_no',$id)->first();
        $charges        = Charge::all();
        $cod_charges    = CodCharge::all();

        if(($parcel->status == 'pending' || $parcel->status == 'pickup-assigned' || $parcel->status == 're-schedule-pickup')
            && ($parcel->merchant->id == Sentinel::getUser()->merchant_id && hasPermission('all_parcel')) || ($parcel->merchant->id == Sentinel::getUser()->merchant_id && $parcel->user_id == Sentinel::getUser()->id)):
            return view('merchant.parcel.edit', compact('parcel', 'charges', 'cod_charges','shops'));
        else:
            return back()->with('danger', __('you_are_not_allowed_to_update_this_parcel'));
        endif;
    }

    public function update(ParcelUpdateRequest $request)
    {
        if($this->parcels->update($request)):
            return redirect()->route('merchant.staff.parcel')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }
    public function parcelDelete(Request $request)
    {
        try {
            $parcel = $this->parcels->get($request->id);
            if (($parcel->merchant->id == Sentinel::getUser()->merchant_id && hasPermission('all_parcel')) || ($parcel->merchant->id == Sentinel::getUser()->merchant_id && $parcel->user_id == Sentinel::getUser()->id)):
                if ($parcel->status == 'deleted'):
                    return back()->with('danger', __('this_parcel_has_already_been_deleted'));
                endif;

                if ($parcel->status == 'pending' || $parcel->status == 'pickup-assigned' || $parcel->status == 're-schedule-pickup'):
                    if($this->parcels->parcelDelete($request)):
                        return redirect()->route('merchant.staff.parcel')->with('success', __('deleted_successfully'));
                    else:
                        return back()->with('danger', __('something_went_wrong_please_try_again'));
                    endif;
                else:
                    return back()->with('danger', __('this_parcel_can_not_be_deleted'));
                endif;
            else:
                return back()->with('danger', __('access_denied'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function parcelCancel(Request $request)
    {
        try {
            $parcel = $this->parcels->get($request->id);
            if (in_array($parcel->status, \Config::get('greenx.merchant_cancel_parcel'))):
                if (($parcel->merchant->id == Sentinel::getUser()->merchant_id && hasPermission('all_parcel'))
                    || ($parcel->merchant->id == Sentinel::getUser()->merchant_id && $parcel->user_id == Sentinel::getUser()->id)):
                    if ($parcel->status == 'cancel'):
                        return back()->with('danger', __('this_parcel_has_already_been_cancelled'));
                    endif;

                    if($this->parcels->parcelCancel($request)):
                        return redirect()->route('merchant.staff.parcel')->with('success', __('updated_successfully'));
                    else:
                        return back()->with('danger', __('something_went_wrong_please_try_again'));
                    endif;
                else:
                    return back()->with('danger', __('access_denied'));
                endif;
            else:
                return back()->with('danger', __('you_are_not_allowed_to_cancel_this_parcel'));
            endif;

        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function parcelReRequest(Request $request)
    {
        try {
            $parcel = $this->parcels->get($request->id);
            if($parcel->status == 'cancel' && ($parcel->merchant->id == Sentinel::getUser()->merchant_id && hasPermission('all_parcel')) || ($parcel->merchant->id == Sentinel::getUser()->merchant_id && $parcel->user_id == Sentinel::getUser()->id)):
                if($this->parcels->parcelStatusUpdate($parcel->id, 're-request', $request->note)):
                    return redirect()->route('merchant.staff.parcel')->with('success', __('updated_successfully'));
                else:
                    return back()->with('danger', __('something_went_wrong_please_try_again'));
                endif;
            else:
                return back()->with('danger', __('you_are_not_allowed_to_update_this_parcel'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function parcelFiltering($slug)
    {
        $charges        = Charge::all();
        $cod_charges    = CodCharge::all();
        $pn             = isset($request->pn) ? $request->pn : '';
        $parcels        = Parcel::where('merchant_id', Sentinel::getUser()->merchant_id)
                                ->when(!hasPermission('all_parcel'), function ($query){
                                    $query->where('user_id',Sentinel::getUser()->id);
                                })->when($slug == 'pending-return', function ($q){
                                    $q->whereIn('status', ['returned-to-greenx','return-assigned-to-merchant','cancel','partially-delivered']);
                                })
                                ->when($slug == 'partially-delivered', function ($q) {
                                    $q->whereIn('status', ['partially-delivered','returned-to-merchant'])
                                        ->where('is_partially_delivered', '=', 1);
                                })
                                ->when($slug != 'pending-return' && $slug != 'partially-delivered', function ($q) use($slug){
                                    $q->where('status', $slug);
                                })->where('parcel_no','like','%' . $pn .'%')
                                ->orderBy('id', 'desc')
                                ->paginate(\Config::get('greenx.parcel_merchant_paginate'));
        return view('merchant.parcel.index', compact('parcels','charges','cod_charges', 'slug','pn'));
    }

    public function filter(Request $request)
    {
        $query = Parcel::query();

        $pn             = isset($request->pn) ? $request->pn : '';

        $query->where('merchant_id', Sentinel::getUser()->merchant_id);
        $query->where('parcel_no','like','%' . $pn .'%');

        if(!hasPermission('all_parcel')){
            $query->where('user_id', Sentinel::getUser()->id);
        }

        if ($request->created_from != "") {
            $created_from = date("Y-m-d", strtotime($request->created_from));
            $query->whereDate('created_at', '>=', "{$created_from}%");
            if ($request->created_to != ""){
                $created_to = date("Y-m-d", strtotime($request->created_to));
                $query->whereDate('created_at', '<=', "{$created_to}%");
            }
        }

        if ($request->customer_name != "") {
            $query->where('customer_name', 'LIKE', "%{$request->customer_name}%");
        }

        if ($request->customer_invoice_no != "") {
            $query->where('customer_invoice_no', 'LIKE', "%{$request->customer_invoice_no}%");
        }

        if ($request->phone_number != "") {
            $query->where('customer_phone_number', $request->phone_number);
        }

        if ($request->status != "any") {
            $query->when($request->status == 'pending-return', function ($q){
                $q->whereIn('status', ['returned-to-greenx','return-assigned-to-merchant','cancel','partially-delivered']);
            })
                ->when($request->status == 'partially-delivered', function ($q) {
                    $q->whereIn('status', ['partially-delivered','returned-to-merchant'])
                        ->where('is_partially_delivered', '=', 1);
                })
                ->when($request->status != 'pending-return' && $request->status != 'partially-delivered', function ($q) use($request){
                    $q->where('status', $request->status);
                });
        }

        if ($request->weight != "any") {
            $query->where('weight', $request->weight);
        }

        if ($request->parcel_type != "any") {
            $query->where('parcel_type', $request->parcel_type);
        }

        if ($request->location != "any") {
            $query->where('location', $request->location);
        }

        if ($request->pickup_date != "") {
            $pickup_date = date("Y-m-d", strtotime($request->pickup_date));
            $query->where('pickup_date', 'LIKE', "%{$pickup_date}%");
        }

        if ($request->delivery_date != "") {
            $delivery_date = date("Y-m-d", strtotime($request->delivery_date));
            $query->where('delivery_date', 'LIKE', "%{$delivery_date}%");
        }

        if ($request->delivered_date != "") {
            $delivered_date = date("Y-m-d", strtotime($request->delivered_date));
            $query->whereHas('events', function ($inner_query) use ($delivered_date) {
                $inner_query->where('title', 'parcel_delivered_event');
                $inner_query->where('created_at', 'LIKE', "%{$delivered_date}%");
            });
        }

        if ($request->has('download')):
            $file_name = 'Filtered Parcels '.date('Y-m-d-s').'.xlsx';
            return Excel::download(new FilteredParcel($query), $file_name);
        endif;

        $parcels = $query->latest()->paginate(\Config::get('greenx.parcel_merchant_paginate'));

        $charges        = Charge::all();
        $cod_charges    = CodCharge::all();
        return view('merchant.parcel.index', compact('parcels','cod_charges','charges','pn'));

    }
}
