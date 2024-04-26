<?php

namespace App\Http\Controllers\Merchant;

use App\Exports\ClosingReport;
use App\Exports\FilteredParcel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Parcel\ParcelStoreRequest;
use App\Http\Requests\Admin\Parcel\ParcelUpdateRequest;
use App\Models\BulkReturn;
use App\Models\Charge;
use App\Models\CodCharge;
use App\Models\DeliveryMan;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\SmsTemplate;
use App\Repositories\Interfaces\DeliveryManInterface;
use App\Repositories\Interfaces\ParcelInterface;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Sentinel;

class ParcelController extends Controller
{
    use ApiReturnFormatTrait;

    protected $parcels;
    protected $delivery_man;

    public function __construct(ParcelInterface $parcels, DeliveryManInterface $delivery_man)
    {
        $this->parcels          = $parcels;
        $this->delivery_man     = $delivery_man;
    }

    private function paginateForMerchant($limit,$pn = '', $phone_no='', $searchKey='')
    {
         $query = Parcel::query();
         $query->where('merchant_id', Sentinel::getUser()->merchant->id);

         if($searchKey!=''){
             $query->where(function($query) use ($searchKey) {
                 $query->where('parcel_no', 'LIKE', "%{$searchKey}%")
                     ->orWhere('customer_phone_number', 'LIKE', "%{$searchKey}%")
                     ->orWhere('customer_name', 'LIKE', "%{$searchKey}%");
             });
         }else{
             if($pn !=''):
                 $query->where('parcel_no','like','%' . $pn .'%');
             endif;
             if($phone_no !=''):
                 $query->where('customer_phone_number','like','%' . $phone_no .'%');
             endif;
         }

         return   $query->orderBy('id', 'desc')->paginate($limit);
    }

    public function index(Request $request)
    {
        $charges        = Charge::all();
        $cod_charges    = CodCharge::all();
        $pn             = isset($request->pn) ? $request->pn : '';
        $phone_no       = isset($request->pn) ? $request->phone_no : '';
        $searchKey            = isset($request->key) ? $request->key : '';
        $parcels        = $this->paginateForMerchant(\Config::get('greenx.parcel_merchant_paginate'),trim($pn), trim($phone_no), trim($searchKey));
        return view('merchant.parcel.index', compact('parcels','cod_charges','charges','pn'));
    }
    public function returnedList(Request $request)
    {

        $status= isset($request->status) ? $request->status : '';

        $query = BulkReturn::query();
        $query->where('merchant_id', Sentinel::getUser()->merchant->id);
        if($status!='' && $status!='all'){
            $query->where('status', $status);
        }
        $query->orderBy('id', 'desc');
        $parcels=$query->paginate(\Config::get('greenx.paginate'));;
        $val = 1;
        return view('merchant.parcel.return-list.index', compact('parcels',  'val'));

    }
    public function returnedView(Request $request, $batch_no)
    {

        $bulk_return = bulkReturn::where('batch_no', $batch_no)->first();
        $return_list= DB::table('parcel_returns as pr')
            ->selectRaw('m.company,  pr.merchant_id, p.parcel_no, m.address, u.first_name, u.last_name, p.customer_invoice_no, p.id, p.status, p.is_partially_delivered, p.customer_name, p.customer_phone_number')
            ->join('merchants as m', 'm.id', 'pr.merchant_id')
            ->join('delivery_men as dm', 'dm.id', 'pr.return_man_id')
            ->join('users as u', 'u.id', 'dm.user_id')
            ->join('parcels as p', 'p.parcel_no', 'pr.parcel_no')
            ->join('bulk_returns as br', 'br.batch_no', 'pr.batch_no')
            ->where('pr.batch_no', $batch_no)
            ->where('pr.status' , "!=", "reversed")
            ->get();
        $merchant = Merchant::find($bulk_return->merchant_id);
        $delivery_man = DeliveryMan::find($bulk_return->delivery_man_id);
        $return_sms = SmsTemplate::where('subject','parcel_otp_event')->first();
        $val= 1;
        $data = [
            'return_list' => $return_list,
            'val' => 1,
            'merchant_name' => $merchant->company,
            "id" => $bulk_return->merchant_id,
            "delivery_man" => $delivery_man,
            "batch_no" => $batch_no,
            "merchant"  => $merchant,
            'bulk_return' => $bulk_return,
            "return_sms" => $return_sms
        ];
        return view('merchant.parcel.return-list.view', $data);

    }


    public function create()
    {
        if (@settingHelper('preferences')->where('title','create_parcel')->first()->merchant):
            $shops          = Sentinel::getUser()->merchant->shops;
            $default_shop   = Sentinel::getUser()->merchant->shops()->where('default',1)->first();
            $charges        = Charge::all();
            $cod_charges    = CodCharge::all();
            return view('merchant.parcel.create', compact('charges', 'cod_charges','shops','default_shop'));
        else:
            return back()->with('danger', __('service_unavailable'));
        endif;
    }

    public function store(ParcelStoreRequest $request)
    {
        //return $request;    // TODO
        if (@settingHelper('preferences')->where('title','create_parcel')->first()->merchant):
            if($this->parcels->store($request)):
                return redirect()->route('merchant.parcel')->with('success', __('created_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return redirect()->route('merchant.parcel')->with('danger', __('service_unavailable'));
        endif;

    }

    public function edit($id)
    {
        $shops          = Sentinel::getUser()->merchant->shops;
        $default_shop   = Sentinel::getUser()->merchant->shops()->where('default',1)->first();
        $parcel= Parcel::where('parcel_no',$id)->first();
        $charges        = Charge::all();
        $cod_charges    = CodCharge::all();
        $marchantCanEditInStatus=marchantCanEditInStatus('percel') ?? [];
        if((in_array($parcel->status, $marchantCanEditInStatus)) && $parcel->merchant->id == Sentinel::getUser()->merchant->id):
            return view('merchant.parcel.edit', compact('parcel', 'charges', 'cod_charges','shops','default_shop'));
        else:
            return back()->with('danger', __('you_are_not_allowed_to_update_this_parcel'));
        endif;
    }

    public function update(ParcelUpdateRequest $request)
    {
        if($this->parcels->update($request)):
            return redirect()->route('merchant.parcel')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function filter(Request $request)
    {
        $query = Parcel::query();

        $pn             = isset($request->pn) ? $request->pn : '';
        $query->where('parcel_no','like','%' . $pn .'%');
        $query->where('merchant_id', Sentinel::getUser()->merchant->id);

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

        // if ($request->weight != "any") {
        //     $query->where('weight', $request->weight);
        // }

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
    public function detail($id){
        try {
            $parcel = Parcel::with('merchant.user','events','hub')->where('parcel_no',$id)->first();
            if($parcel->merchant->id == Sentinel::getUser()->merchant->id):
                $charges        = Charge::all();
                $cod_charges    = CodCharge::all();
                return view('merchant.parcel.detail', compact('parcel','cod_charges','charges'));
            else:
                return back()->with('danger', __('you_are_not_allowed'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }
    public function print($id){
        try {
            $parcel= Parcel::where('parcel_no',$id)->first();
            if($parcel->merchant->id == Sentinel::getUser()->merchant->id):
                $charges        = Charge::all();
                $cod_charges    = CodCharge::all();
                return view('merchant.parcel.print', compact('parcel','cod_charges','charges'));
            else:
                return back()->with('danger', __('you_are_not_allowed'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function parcelStatusUpdate($id, $status)
    {
        $parcel = $this->parcels->get($id);
        if(($parcel->status == 'pending' || $parcel->status == 'pickup-assigned' || $parcel->status == 're-schedule-pickup' || $parcel->status == 'cancel') && $parcel->merchant->id == Sentinel::getUser()->merchant->id):
            if($this->parcels->parcelStatusUpdate($id, $status, '')):
                $success[0] = __('updated_successfully');
                $success[1] = 'success';
                $success[2] = __('updated');
                return response()->json($success);
            else:
                $success[0] = __('something_went_wrong_please_try_again');
                $success[1] = 'error';
                $success[2] = __('oops');
                return response()->json($success);
            endif;
        else:
            return back()->with('danger', __('you_are_not_allowed_to_update_this_parcel'));
        endif;
    }


    public function duplicate($id){
        try {
            if (@settingHelper('preferences')->where('title','create_parcel')->first()->merchant):
                $parcel= Parcel::where('parcel_no',$id)->first();
                $shops = Sentinel::getUser()->merchant->shops;
                $default_shop = Sentinel::getUser()->merchant->shops()->where('default',1)->first();
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

    public function parcelCancel(Request $request)
    {
        try {
            $parcel = $this->parcels->get($request->id);
            if ($parcel->merchant->id == Sentinel::getUser()->merchant->id && in_array($parcel->status, \Config::get('greenx.merchant_cancel_parcel') ?? [])):
                if ($parcel->status == 'cancel'):
                    return back()->with('danger', __('this_parcel_has_already_been_cancelled'));
                endif;

                if($this->parcels->parcelCancel($request)):
                    return redirect()->route('merchant.parcel')->with('success', __('updated_successfully'));
                else:
                    return back()->with('danger', __('something_went_wrong_please_try_again'));
                endif;
            else:
                return back()->with('danger', 'You are not allowed to cancel this parcel.');
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }
    public function parcelDelete(Request $request)
    {
        try {
            $parcel = $this->parcels->get($request->id);
            if ($parcel->merchant->id == Sentinel::getUser()->merchant->id):
                if ($parcel->status == 'deleted'):
                    return back()->with('danger', __('this_parcel_has_already_been_deleted'));
                endif;

                if ($parcel->status == 'pending' || $parcel->status == 'pickup-assigned' || $parcel->status == 're-schedule-pickup'):
                    if($this->parcels->parcelDelete($request)):
                        return redirect()->route('merchant.parcel')->with('success', __('deleted_successfully'));
                    else:
                        return back()->with('danger', __('something_went_wrong_please_try_again'));
                    endif;
                else:
                    return back()->with('danger', __('this_parcel_can_not_be_deleted'));
                endif;
            else:
                return back()->with('danger', __('you_are_not_allowed'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function parcelReRequest(Request $request)
    {
        try {
            $parcel = $this->parcels->get($request->id);
            if($parcel->status == 'cancel' && $parcel->merchant->id == Sentinel::getUser()->merchant->id):
                if($this->parcels->parcelStatusUpdate($parcel->id, 're-request', $request->note)):
                    return redirect()->route('merchant.parcel')->with('success', __('updated_successfully'));
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
        $parcels        = Parcel::where('merchant_id', Sentinel::getUser()->merchant->id)->where('parcel_no','like','%' . $pn .'%')
                            ->when($slug == 'pending-return', function ($q){
                                $q->whereIn('status', ['returned-to-greenx','return-assigned-to-merchant','cancel','partially-delivered']);
                            })
                                ->when($slug == 'partially-delivered', function ($q) {
                                    $q->whereIn('status', ['partially-delivered','returned-to-merchant'])
                                        ->where('is_partially_delivered', '=', 1);
                                })
                                ->when($slug != 'pending-return' && $slug != 'partially-delivered', function ($q) use($slug){
                                    $q->where('status', $slug);
                                })
            ->orderBy('id', 'desc')->paginate(\Config::get('greenx.parcel_merchant_paginate'));
        return view('merchant.parcel.index', compact('parcels','charges','cod_charges', 'slug','pn'));
    }

    public function track($id)
    {
        try{
            $parcel = Parcel::where('parcel_no', $id)->latest()->first();

            if (!blank($parcel)):
                $data['parcel_no'] = $parcel->parcel_no;
                $data['status'] = __($parcel->status);

                $merchant['merchan_name']       = $parcel->merchant->user->first_name.' '.$parcel->merchant->user->last_name;
                $merchant['merchant_company']   = $parcel->merchant->company;
                $merchant['phone_number']       = $parcel->merchant->phone_number;
                $merchant['address']            = $parcel->merchant->address;
                $merchant['email']              = $parcel->merchant->user->email;
                $merchant['created']            = date('M d, Y g:i a', strtotime($parcel->created_at));
                $merchant['parcel_type']        = __($parcel->parcel_type);
                $merchant['total_charge']       = __($parcel->total_delivery_charge).' '.__('tk');
                $merchant['pickup_person']      = @$parcel->pickupMan->user['first_name'].' '.@$parcel->pickupMan->user['last_name'];
                $merchant['delivery_person']    = @$parcel->deliveryMan->user['first_name'].' '.@$parcel->deliveryMan->user['last_name'];
                $merchant['return_delivery_person']    = @$parcel->returnDeliveryMan->user['first_name'].' '.@$parcel->returnDeliveryMan->user['last_name'];
                if($parcel->status != 'pending' && $parcel->status
                    != 'pickup-assigned' && $parcel->status != 're-schedule-pickup'
                    && $parcel->status != 'received-by-pickup-man'):
                    if (!blank($parcel->hub)):
                        $merchant['hub']                = @$parcel->hub->name.' ('.@$parcel->hub->address.')';
                    else:
                        $merchant['hub']                = '';
                    endif;
                endif;
                if($parcel->status == 'transferred-to-hub') :
                    $merchant['transferring_to_hub']    = @$parcel->transferToHub->name.' ('.@$parcel->transferToHub->address.')';
                endif;
                $merchant['pickup']             = date('M d, Y', strtotime($parcel->pickup_date));
                $merchant['delivery']           = date('M d, Y', strtotime($parcel->delivery_date));
                $merchant['pickup']             = date('M d, Y', strtotime($parcel->pickup_date));

                if ($parcel->status == 'delivered' || $parcel->status == 'delivered-and-verified'):
                    $merchant['delivered_at']       = date('M d, Y g:i A', strtotime($parcel->updated_at));
                endif;

                $customer['id']                 = $parcel->parcel_no;
                $customer['invno']              = $parcel->customer_invoice_no;
                $customer['customer_name']      = $parcel->customer_name;
                $customer['customer_mobile_no'] = $parcel->customer_phone_number;
                $customer['customer_mobile_no'] = $parcel->customer_address;
                $customer['location']           = __($parcel->location);
                $customer['note']               = __($parcel->note);
                $customer['weight']             = $parcel->weight.' '.__('kg');
                $customer['total_cod']          = $parcel->price.' '.__('tk');

                $events = $parcel->events;

                foreach ($events as $event):
                    $event['event']             = $event->title;
                    $event['title']             = __($event->title);
                    $event['date']              = date('d M Y', strtotime($event->created_at));
                    $event['time']              = date('h:i a', strtotime($event->created_at));
                    $event['processed_by']      = $event->user['first_name'].' '.$event->user['last_name'];
                    $event['pickup_man']        = @$event->pickupPerson->user->first_name.' '.@$event->pickupPerson->user->last_name;
                    $event['pickup_man_phone']  = @$event->pickupPerson->phone_number ?? '';
                    $event['delivery_man']      = @$event->deliveryPerson->user->first_name.' '.@$event->deliveryPerson->user->last_name;
                    $event['delivery_man_phone'] = @$event->deliveryPerson->phone_number ?? '';
                    $event['return_delivery_man']      = @$event->returnPerson->user->first_name.' '.@$event->returnPerson->user->last_name;
                    $event['return_delivery_man_phone'] = @$event->returnPerson->phone_number ?? '';
                    $hub = '';
                    if(!blank($event->hub)) :
                        $hub    = @$event->hub->name.' ('.@$event->hub->address.')';
                    endif;
                    $event['note'] = $event->cancel_note !=''? $event->cancel_note : '';

                    unset($event->user);
                    unset($event->pickupPerson);
                    unset($event->deliveryPerson);
                    unset($event->created_at);
                    unset($event->updated_at);
                    unset($event->reverse_status);
                    unset($event->pickup_man_id);
                    unset($event->delivery_man_id);
                    unset($event->user_id);
                    unset($event->parcel_id);
                    unset($event->return_delivery_man_id);
                    unset($event->hub);

                    $event['hub'] = $hub;
                endforeach;

                $data['merchant']   = $merchant;
                $data['customer']   = $customer;
                $data['events']     = $events;

                return $this->responseWithSuccess(__('successfully_found'), $data, 200);
            else:
                return $this->responseWithError(__('parcel_not_found'), [], 404);
            endif;

        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function download()
    {
        $merchant = Sentinel::getUser()->merchant;
        $file_name = $merchant->company.' '.'- Closing '.date('Y-m-d').'.xlsx';
        return Excel::download(new ClosingReport($merchant->id), $file_name);
    }
}
