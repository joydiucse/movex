<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ClosingReport;
use App\Exports\FilteredParcel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Parcel\ParcelStoreRequest;
use App\Http\Requests\Admin\Parcel\ParcelUpdateRequest;
use App\Http\Requests\Admin\Parcel\PartialDeliveryRequest;
use App\Http\Requests\Admin\Parcel\TransferToHubRequest;
use App\Models\Hub;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\Shop;
use App\Models\ThirdParty;
use App\Models\User;
use App\Repositories\Interfaces\ParcelInterface;
use App\Repositories\Interfaces\DeliveryManInterface;
use App\Traits\SmsSenderTrait;
use Database\Seeders\CustomerParcelSmsSeeder;
use Illuminate\Http\Request;
use App\Models\Charge;
use App\Models\CodCharge;
use Maatwebsite\Excel\Facades\Excel;
use Sentinel;
use DB;
use Exception;
use App\Models\CustomerParcelSmsTemplates;
use App\Models\Setting;
use App\Exports\ExportParcel;
use App\Models\ParcelDeliver;
use App\Models\SmsTemplate;

class ParcelController extends Controller
{
    use SmsSenderTrait;
    protected $parcels;
    protected $delivery_man;

    public function __construct(ParcelInterface $parcels, DeliveryManInterface $delivery_man)
    {
        $this->parcels          = $parcels;
        $this->delivery_man     = $delivery_man;
    }

    public function index()
    {
        $charges = Charge::all();
        $cod_charges = CodCharge::all();
        $parcels = $this->parcels->paginate(\Config::get('greenx.parcel_merchant_paginate'));
        $hubs = Hub::all();
        $third_parties = ThirdParty::where('status', true)->orderBy('name')->get();
        return view('admin.parcel.index', compact('parcels','charges','cod_charges','hubs','third_parties'));
    }
    public function create()
    {
        if (@settingHelper('preferences')->where('title','create_parcel')->first()->staff):
            $charges = Charge::all();
            $hubs = Hub::all();
            return view('admin.parcel.create', compact( 'charges','hubs'));
        else:
            return back()->with('danger', __('service_unavailable'));
        endif;

    }
    public function store(ParcelStoreRequest $request)
    {
        if (@settingHelper('preferences')->where('title','create_parcel')->first()->staff):
            if($this->parcels->store($request)):
                return redirect()->route('parcel')->with('success', __('created_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return redirect()->route('parcel')->with('danger', __('service_unavailable'));
        endif;
    }
    public function edit($id)
    {
        //$parcel = $this->parcels->get($id);
        $parcel= Parcel::where('parcel_no',$id)->first();

        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):

            $charges    = Charge::all();
            $hubs       = Hub::all();

            if($parcel->status == 'pending'
                || $parcel->status == 'pickup-assigned'
                || $parcel->status == 're-schedule-pickup'
                || $parcel->status == 'received-by-pickup-man'
                || $parcel->status == "received"
                || $parcel->status == "transferred-to-hub"
                || $parcel->status == "delivery-assigned"
                || $parcel->status == "re-schedule-delivery"
                || ($parcel->status == "returned-to-greenx" && $parcel->is_partially_delivered == false)
                || ($parcel->status == "return-assigned-to-merchant" && $parcel->is_partially_delivered == false)) :

                return view('admin.parcel.edit', compact('parcel', 'charges','hubs'));
            else:
                return back()->with('danger', __('you_are_not_allowed_to_update_this_parcel'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function update(ParcelUpdateRequest $request)
    {
        $parcel = $this->parcels->get($request->id);
        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):

            if($this->parcels->update($request)):
                return redirect()->route('parcel')->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;

        else:
            return back()->with('danger', __('access_denied'));
        endif;

    }

    public function parcelDelete(Request $request)
    {
        $parcel = $this->parcels->get($request->id);

        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            ||$parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):

            if ($parcel->status == 'deleted'):
                return back()->with('danger', __('this_parcel_has_already_been_deleted'));
            endif;

            if($this->parcels->parcelDelete($request)):
                return redirect()->route('parcel')->with('success', __('deleted_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function assignPickupMan(Request $request)
    {
        $parcel = $this->parcels->get($request->id);

        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            ||$parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):

            if($this->parcels->assignPickupMan($request)):
                return redirect()->back()->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }
    public function assignDeliveryMan(Request $request)
    {
        $parcel = $this->parcels->get($request->id);

        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            ||$parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):
            if($this->parcels->assignDeliveryMan($request)):
                return redirect()->back()->with('success', __('delivery_man_assigned_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function reSchedulePickup(Request $request)
    {
        $data = $this->parcels->reSchedulePickup($request);
        return response()->json($data);
    }

    public function parcelCod(Request $request)
    {

        $parcel = $this->parcels->get($request->id);
        $data[1] = $parcel->price;

        return response()->json($data);
    }

    public function reSchedulePickupMan(Request $request)
    {
        if($this->parcels->reSchedulePickupMan($request)):
            return redirect()->back()->with('success', __('pickup_rescheduled_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function reScheduleDelivery(Request $request)
    {
        $data = $this->parcels->reScheduleDelivery($request);
        return response()->json($data);

    }

    public function reScheduleDeliveryMan(Request $request)
    {
        if($this->assignReverse($request)):
            if($this->parcels->reScheduleDeliveryMan($request)):
                return redirect()->back()->with('success', __('delivery_rescheduled_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function returnAssignToMerchant(Request $request)
    {
        if($this->parcels->returnAssignToMerchant($request)):
            return redirect()->back()->with('success', __('return_assign_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function parcelCancel(Request $request)
    {
        $parcel = $this->parcels->get($request->id);
        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            ||$parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):
            if ($parcel->status == 'cancel'):
                return back()->with('danger', __('this_parcel_has_already_been_cancelled'));
            endif;

            if ($parcel->status == 'delivered' || $parcel->status == 'delivered-and-verified' || $parcel->status == 'returned-to-merchant' || $parcel->status == 'partially-delivered'):
                return back()->with('danger', __('this_parcel_can_not_be_cancelled'));
            endif;

            if($this->parcels->parcelCancel($request)):
                return redirect()->back()->with('success', __('cancelled_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function parcelReceiveByPickupman(Request $request)
    {
        $parcel = $this->parcels->get($request->id);
        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):
            if ($parcel->status == 'received-by-pickup-man'):
                return back()->with('danger', __('this_parcel_has_already_been_received_by_pickup_man'));
            endif;

            if($this->parcels->parcelStatusUpdate($parcel->id, 'received-by-pickup-man', $request->note)):
                return redirect()->back()->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function parcelReceive(Request $request)
    {
        $parcel = $this->parcels->get($request->id);
        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):
            if ($parcel->status == 'received'):
                return back()->with('danger', __('this_parcel_has_already_been_received'));
            endif;

            if($this->parcels->parcelStatusUpdate($parcel->id, 'received', $request->note, $request->hub)):
                return redirect()->back()->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function parcelDelivery(Request $request)
    {
        $parcel = $this->parcels->get($request->id);
        $otp_permission = settingHelper('delivery_otp');
        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):

            if ($parcel->status == 'partially-delivered' || $parcel->status == 'delivered' || $parcel->status == 'delivered-and-verified'):
                return back()->with('danger', __('this_parcel_has_already_confirmed_as_delivered'));
            endif;

            if(!empty($parcel->otp) && ($otp_permission->value =='all' || $otp_permission->value =='conditional')){

                if(($parcel->otp != $request->otp_code) && ($parcel->status == 'delivery-assigned')):

                    return back()->with('danger', __('please_provide_correct_otp'));
                endif;
            }

            if($this->parcels->parcelStatusUpdate($parcel->id, 'delivered', $request->note)):
                return redirect()->back()->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;


        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function partialDelivery(PartialDeliveryRequest $request)
    {

        $parcel = $this->parcels->get($request->id);
        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):
            if ($parcel->status == 'partially-delivered' || $parcel->status == 'delivered' || $parcel->status == 'delivered-and-verified'):
                return back()->with('danger', __('this_parcel_has_already_confirmed_as_delivered'));
            endif;

            if($this->parcels->partialDelivery($request)):
                return redirect()->back()->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function parcelReturnToGreenx(Request $request)
    {
        $parcel = $this->parcels->get($request->id);
        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):

            if ($parcel->status == 'returned-to-greenx'):
                return back()->with('danger', __('this_parcel_has_already_confirmed_as_returned_to_greenx'));
            endif;

            if($this->parcels->parcelStatusUpdate($parcel->id, 'returned-to-greenx', $request->note)):
                return redirect()->back()->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function returnToMerchant(Request $request)
    {
        $otp_code =  $request->opt_code;
        $parcel = $this->parcels->get($request->id);
        if(hasPermission('parcel_returned_to_merchant') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):

            if ($parcel->status == 'returned-to-merchant'):
                return back()->with('danger', __('this_parcel_has_already_confirmed_as_returned_to_merchant'));
            endif;

            if(isset($request->opt_code)):
                if($this->parcelReturToMarchenConfirm($request->id, $otp_code) == true){
                    if($this->parcels->parcelStatusUpdate($parcel->id, 'returned-to-merchant', $request->note)):
                        return redirect()->back()->with('success', __('updated_successfully'));
                    else:
                        return back()->with('danger', __('something_went_wrong_please_try_again'));
                    endif;
                }else{
                    return back()->with('danger', __('Sorry !! OPT Code not match'));
                }
            else:
                if($this->parcels->parcelStatusUpdate($parcel->id, 'returned-to-merchant', $request->note)):
                        return redirect()->back()->with('success', __('updated_successfully'));
                    else:
                        return back()->with('danger', __('something_went_wrong_please_try_again'));
                    endif;
            endif;

        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }


    public function parcelGenerateOtp(Request $request)
    {
         $parcel_id = $request->parcel_id;
         $opt = $this->parcels->parcelOtpGenerate($parcel_id);
         if($opt['success'] == true){
            return "<p> OTP  Send To This Number <b>".$opt['phone']."</b>   <br> Please Insert The OTP Code For Parcel Return </p>";

         }else{
            return "OTP code send fail please try again";
         }
    }

    public function parcelGenerateOtpCheck(Request $request)
    {
         $parcel_id = $request->parcel_id;
         $otp_code = $request->otp_code;
         $verify_otp = $this->parcels->otpCodevarified($parcel_id, $otp_code);
         if($verify_otp == true)
         {
            return true;
         }else{
            return false;
         }

    }


    public function parcelReturToMarchenConfirm($id, $otp)
    {
           return  $this->parcels->merchantReturnConfirm($id, $otp);
    }

    public function reverseFromCancel(Request $request)
    {
        $parcel = $this->parcels->get($request->id);
        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):

            if($this->parcels->reverseUpdate($parcel->id, $parcel->status_before_cancel, $request->note)):
                return redirect()->route('parcel')->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function transferToHub(TransferToHubRequest $request)
    {
        $parcel = $this->parcels->get($request->id);
        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):
            if ($parcel->hub_id == $request->hub):
                return back()->with('danger', __('hub_must_be_different'));
            endif;

            if ($parcel->status == 'transferred-to-hub'):
                return back()->with('danger', __('this_parcel_has_already_assigned_for_transfer_to_hub'));
            endif;

            if($this->parcels->parcelStatusUpdate($parcel->id, 'transferred-to-hub', $request->note, $request->hub, $request->delivery_man)):
                return redirect()->route('parcel')->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function transferReceiveToHub(Request $request)
    {
        $parcel = $this->parcels->get($request->id);

        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):

            if ($parcel->status == 'transferred-received-by-hub'):
                return back()->with('danger', __('this_parcel_has_already_assigned_for_transfer_to_hub'));
            endif;

            if($this->parcels->parcelStatusUpdate($parcel->id, 'transferred-received-by-hub', $request->note, $parcel->transfer_to_hub_id)):
                return redirect()->route('parcel')->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }

    public function deliveryReverse(Request $request)
    {
        $parcel = $this->parcels->get($request->id);

        if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
            || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):
            if($this->parcels->deliveryReverse($request)):
                //return redirect()->route('parcel')->with('success', __('updated_successfully'));
                return redirect()->back()->with('success', __('updated_successfully'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('access_denied'));
        endif;
    }
    public function filter(Request $request)
    {
        $charges = Charge::all();
        $cod_charges = CodCharge::all();
        $hubs = Hub::all();
        $third_parties = ThirdParty::where('status', true)->orderBy('name')->get();

        $query = Parcel::query();

        if(!hasPermission('read_all_parcel')){
            $query->where(function ($q){
                $q->where('hub_id', \Sentinel::getUser()->hub_id)
                    ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                    ->orWhereNull('pickup_hub_id')
                    ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                });
            }

        if ($request->parcel_no != "") {
            $query->where('parcel_no',$request->parcel_no);
            $parcels = $query->paginate(\Config::get('greenx.paginate'));

            return view('admin.parcel.index', compact('parcels', 'cod_charges', 'charges', 'hubs','third_parties'));
        }

        if ($request->phone_no != "") {
            $query->where('customer_phone_number','LIKE', '%'.$request->phone_no);
            $parcels = $query->paginate(\Config::get('greenx.paginate'));

            return view('admin.parcel.index', compact('parcels', 'cod_charges', 'charges', 'hubs','third_parties'));
        }

        if ($request->created_from != "") {
            $created_from = date("Y-m-d", strtotime($request->created_from));
            $query->whereDate('created_at', '>=', "{$created_from}%");
            if ($request->created_to != ""){
                $created_to = date("Y-m-d", strtotime($request->created_to));
                $query->whereDate('created_at', '<=', "{$created_to}%");
            }
        }

        if ($request->pickup_hub != 'all'){
            $query->when($request->pickup_hub == 'pending', function ($search){
                    $search->where('pickup_hub_id', null);
                })->when($request->pickup_hub != 'pending', function ($search) use ($request){
                    $search->where('pickup_hub_id', $request->pickup_hub);
            });
        }

        if ($request->hub != "any") {
            $query->where('hub_id', $request->hub);
        }

        if ($request->merchant != "") {
            $query->where('merchant_id', $request->merchant);
        }
        if ($request->phone_number != "") {
            $query->whereHas('merchant', function ($inner_query) use ($request) {
                $inner_query->where('phone_number', 'LIKE', "%{$request->phone_number}%");
            });
        }

        if ($request->customer_invoice_no != "") {
            $query->where('customer_invoice_no', 'LIKE', "%{$request->customer_invoice_no}%");
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

        if ($request->pickup_man != "any") {
            $query->where('pickup_man_id', $request->pickup_man);
        }

        if ($request->delivery_man != "any") {
            $query->where('delivery_man_id', $request->delivery_man);
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

        if ($request->third_party != "any") {
            $query->where('third_party_id', $request->third_party);
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

        return view('admin.parcel.index', compact('parcels','cod_charges','charges','hubs','third_parties'));
    }

    public function shops(Request $request){
        $requested_merchant = Merchant::findOrfail($request->merchant_id);

        $shops = $requested_merchant->shops;
        return view('admin.parcel.shops', compact('shops',))->render();
    }

    public function shop(Request $request){
        $shop = Shop::find($request->shop_id);

        $data['shop_phone_number'] = $shop->shop_phone_number;
        $data['address'] = $shop->address;
        return response()->json($data);
    }

    public function default(Request $request){
        $default_shop = Shop::where('merchant_id',$request->merchant_id)->where('default',1)->first();
        $pickup_hub   = Merchant::find($request->merchant_id)->user->hub_id;

        $data['shop_phone_number']  = $default_shop->shop_phone_number;
        $data['address']            = $default_shop->address;

        $hubs       = Hub::all();

        $options    =  view('admin.parcel.hubs', compact('hubs','pickup_hub'))->render();

        $data['pickup_hub'] = $options;
        return response()->json($data);
    }

    public function merchantStaff(Request $request)
    {
        $staffs   = Merchant::find($request->merchant_id)->staffs;

        return  view('admin.parcel.staffs', compact('staffs'))->render();
    }


    public function detail($id){
        try {
            $parcel = Parcel::with('merchant.user','events','hub')->where('parcel_no', $id)->first();
            if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
                || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):

                $charges = Charge::all();
                $cod_charges = CodCharge::all();
                return view('admin.parcel.detail', compact('parcel','cod_charges','charges'));
            else:
                return  back()->with('danger',__('access_denied'));
            endif;

        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function print($id){
        try {
            $parcel= Parcel::where('parcel_no',$id)->first();
            if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
                || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):

                $charges = Charge::all();
                $cod_charges = CodCharge::all();
                $delivery_men = $this->delivery_man->all();
                return view('merchant.parcel.print', compact('parcel','cod_charges','charges', 'delivery_men'));
            else:
                return  back()->with('danger',__('access_denied'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function duplicate($id){
        try {
            if (@settingHelper('preferences')->where('title','create_parcel')->first()->staff):
                $parcel= Parcel::where('parcel_no',$id)->first();
                $charges = Charge::all();
                $hubs = Hub::all();

                return view('admin.parcel.create', compact('parcel', 'charges','hubs'));
            else:
                return back()->with('danger', __('service_unavailable'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function sticker($id){
        try {
            $parcel= Parcel::where('parcel_no',$id)->first();
            if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
                || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):
                return view('admin.parcel.sticker', compact('parcel'));
            else:
                return  back()->with('danger',__('access_denied'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function notifyPickupMan($id){
        try {
            $parcel= Parcel::where('parcel_no',$id)->first();

            $sms_body = $parcel->pickupMan->user->first_name.', a pickup has been assigned to you. Address: '.$parcel->pickup_address.', Phone number: '.$parcel->pickup_shop_phone_number.', Pickup date: '.$parcel->pickup_date;

            if ($this->smsSender('notify_pickup_man', $parcel->pickupMan->phone_number, $sms_body, true)):
                return back()->with('success', __('notified_successfully'));
            else:
                return back()->with('danger', __('unable_to_notify'));
            endif;

        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function reverseOptions(Request $request){

        $status = $this->parcels->get($request->id)->status;
        $is_partially_delivered = $this->parcels->get($request->id)->is_partially_delivered;

        return view('admin.parcel.reverse-options', compact('status','is_partially_delivered'))->render();
    }

    public function transferOptions(Request $request){

        $current_hub = $this->parcels->get($request->id)->hub_id;

        $hubs = Hub::where('id', '!=', $current_hub)->get();

        return view('admin.parcel.transfer-options', compact('hubs'))->render();
    }

    public function reverseUpdate($id, $status, $note = '')
    {

        if($this->parcels->reverseUpdate($id, $status)):
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
    }

    public function parcelFiltering($slug)
    {
        $charges        = Charge::all();
        $cod_charges    = CodCharge::all();
        $hubs           = Hub::all();
        $third_parties  = ThirdParty::where('status', true)->orderBy('name')->get();

        if($slug =='edited'){
            $parcels =Parcel::select('parcels.*', 'parcel_events.id as event_id')
                ->join('parcel_events', 'parcel_events.parcel_id', 'parcels.id')
                ->when($slug == 'edited', function ($q){
                    $q->where('parcel_events.title', 'parcel_update_event');
                })
                ->when(!hasPermission('read_all_parcel'), function ($query){
                    $query->where(function ($q){
                        $q->where('hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                            ->orWhereNull('pickup_hub_id')
                            ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                    });
                })
                ->groupBy('parcels.id')
                ->orderByDesc('parcels.id')
                ->paginate(\Config::get('greenx.parcel_merchant_paginate'));
        }else{
            $parcels = Parcel::when($slug == 'pending-return', function ($q){
                $q->whereIn('status', ['returned-to-greenx','return-assigned-to-merchant','cancel','partially-delivered']);
            })
            ->when($slug == 'partially-delivered', function ($q) {
                $q->whereIn('status', ['partially-delivered','returned-to-merchant'])
                    ->where('is_partially_delivered', '=', 1);
            })
            ->when($slug != 'pending-return' && $slug != 'partially-delivered' &&  $slug !='today-attempt', function ($q) use($slug){
                $q->where('status', $slug);
            })
            ->when($slug == 'today-attempt', function ($q) {
                $q->whereIn('status', ['re-schedule-delivery'])
                    ->where('delivery_date',  date('Y-m-d'));
            })
            ->when(!hasPermission('read_all_parcel'), function ($query){
                $query->where(function ($q){
                    $q->where('hub_id', \Sentinel::getUser()->hub_id)
                        ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                        ->orWhereNull('pickup_hub_id')
                        ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
                });
            })
            ->orderByDesc('id')
            ->paginate(\Config::get('greenx.parcel_merchant_paginate'));
        }

        return view('admin.parcel.index', compact('parcels','charges','cod_charges', 'slug','hubs','third_parties'));
    }

    public function chargeDetails(Request $request)
    {
        $data           = $this->parcels->chargeDetails($request);
        return response()->json($data);
    }

    public function customerDetails(Request $request)
    {
        $data           = $this->parcels->customerDetails($request);

        return response()->json($data);
    }

    public function location(Request $request)
    {
        $data['location'] = $this->parcels->get($request->id)->location;

        return response()->json($data);
    }

    public function download($id)
    {
        $merchant = Merchant::find($id);
        $file_name = $merchant->company.' '.'- Closing '.date('Y-m-d').'.xlsx';
        return Excel::download(new ClosingReport($id), $file_name);
    }

    public function createPaperflyParcel(Request $request)
    {
         try {
             $response = $this->parcels->createPaperflyParcel($request);

            if ($response === true):
                return back()->with('success', __('created_successfully'));
            else:
                return back()->with('danger', $response);
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function getThanaUnion(Request $request)
    {
        return $this->parcels->getThanaUnion($request);
    }

    public function getDistrict()
    {
        return $this->parcels->getDistrict();
    }

    public function trackParcel($id)
    {
        return $this->parcels->trackParcel($id);
    }


    public function returnList()
    {
            $parcels = $this->parcels->returnList();
            $val = 1;
            return view('admin.parcel.return.index', compact('parcels', 'val'));
    }

    public function returnListFilter(Request $request)
    {
        $parcels = $this->parcels->returnListFilter($request);
        $val = 1;
        return view('admin.parcel.return.index', compact('parcels', 'val'));
    }

    public function searchMerchant(Request $request)
    {
         $merchant_name = $request->merchant_name;
         $parcels  = $this->parcels->searchMerchant($merchant_name);
         $val = 1;
         $output =  view('admin.parcel.return.search-merchant', compact('parcels', 'val'));

        return $output;


    }

    public function assignList($id)
    {
        $parcels =  $this->parcels->assignList($id);
        $val = 1;
        $merchant_id = $id;
        $return_man= Parcel::select('return_delivery_man_id')->where('status','return-assigned-to-merchant')->where('merchant_id', $id)->first();
        $return_man_id = $return_man->return_delivery_man_id;
        return view('admin.parcel.return.merchant_return', compact('parcels', 'val', 'return_man_id', 'merchant_id'));
    }


    public function bulkOtpCode(Request $request)
    {
        $merchant_id = $request->merchant_id;
        $otp= $this->parcels->bulkOtpCode($request);
       if($otp['success'] == true){
        return "<p> OTP  Send To This Number <b> <span class='text-info'>".$otp['phone']." </span> </b>  Please Insert The OTP Code For Parcel Return </p>";
        }else{
            return "OTP code send fail please try again";
        }
    }

    public function bulkOtpCodeCheck(Request $request)
    {

        $otp_success = $this->parcels->bulkOtpCodeCheck($request);
        if($otp_success == true){
            return true;
        }else{
            return false;
        }
    }

    public function confirmBulkReturn(Request $request)
    {

        if($this->parcels->confirmBulkReturn($request)){
            return redirect()->route('parcel')->with('success', __('updated_successfully'));
        }else{
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function parcelReturnReverse(Request $request)
    {

        if($this->parcels->parcelReturnReverse($request)){
            return true;
        }else{
            return "Something went wrong, Please try again.";
        }

    }

    public function parcelList()
    {
        $charges = Charge::all();
        $cod_charges = CodCharge::all();
        $parcels = $this->parcels->parcelList(\Config::get('greenx.parcel_merchant_paginate'));
        $hubs = Hub::all();
        $third_parties = ThirdParty::where('status', true)->orderBy('name')->get();
        return view('admin.parcel.return.single-return', compact('parcels','charges','cod_charges','hubs','third_parties'));
    }

    public function bulkStickerPrint(Request $request)
    {
        $pacels = $request->parcel_id;

        if(!empty($pacels)):
            try{
                $parcel_id =  implode(",", $pacels);
                $parcels  = Parcel::whereRaw("id in ($parcel_id) ")->get();
                if(hasPermission('read_all_parcel')):
                        return view('admin.parcel.bulk-sticker', compact('parcels'));
                else:
                    return  back()->with('danger',__('access_denied'));
                endif;

            }catch(Exception $e){
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            }
       else:
            return back()->with('danger', __('Sorry_parcel_not_select'));
       endif;
    }

    public function bulkReturnSticker(Request $request)
    {
         $pacels = $request->parcel_id;
        if(!empty($pacels)):
            try{
                $parcel_id =  implode(",", $pacels);
                $parcels  = Parcel::whereRaw("id in ($parcel_id) ")->get();
                if(hasPermission('read_all_parcel')):
                        return view('admin.parcel.bulk-return-sticker', compact('parcels'));
                else:
                    return  back()->with('danger',__('access_denied'));
                endif;

            }catch(Exception $e){
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            }
       else:
            return back()->with('danger', __('Sorry_parcel_not_select'));
       endif;
    }


    public function bulkParcelPrint(Request $request)
    {

        $pacels = $request->parcel_id;
        if(!empty($pacels)):
            try {
                $parcel_id =  implode(",", $pacels);
                $parcels  = Parcel::whereRaw("id in ($parcel_id) ")->get();
                if(hasPermission('read_all_parcel')):
                    $charges = Charge::all();
                    $cod_charges = CodCharge::all();
                    $delivery_men = $this->delivery_man->all();
                    $val= 0;
                    return view('admin.parcel.bulk-print', compact('parcels','cod_charges','charges', 'delivery_men', 'val'));
                else:
                    return  back()->with('danger',__('access_denied'));
                endif;
            } catch (\Exception $e){
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            }
       else:
            return back()->with('danger', __('Sorry_parcel_not_select'));
       endif;
    }


    public function ReturnSticker($id)
    {
        try {
            $parcel= Parcel::where('parcel_no',$id)->first();
            if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
                || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):
                return view('admin.parcel.return-sticker', compact('parcel'));
            else:
                return  back()->with('danger',__('access_denied'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function ReturnPrint($id)
    {

        try {
            $parcel= Parcel::where('parcel_no',$id)->first();
            if(hasPermission('read_all_parcel') || $parcel->hub_id == Sentinel::getUser()->hub_id || $parcel->pickup_hub_id == ''
                || $parcel->pickup_hub_id == Sentinel::getUser()->hub_id || $parcel->transfer_to_hub_id == Sentinel::getUser()->hub_id):
                return view('admin.parcel.return-print', compact('parcel'));
            else:
                return  back()->with('danger',__('access_denied'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }


    public function bulkReturnParcel(Request $request)
    {
        $pacels = $request->parcel_id;
        if(!empty($pacels)):
            try {
                $parcel_id =  implode(",", $pacels);
                if(hasPermission('read_all_parcel')):
                   $parcels_summary  = Parcel::selectRaw("count(parcel_no) as total_parcel, sum(cod_charge) as total_cod, merchant_id ")->whereRaw("id in ($parcel_id) ")->groupBy('merchant_id')->get();
                    $merchant_return  = array();
                    foreach($parcels_summary as $val)
                    {
                        $parcels  = Parcel::select('Parcel_no', 'customer_invoice_no')->whereRaw("id in ($parcel_id) ")->where('merchant_id', $val->merchant_id)->get();
                        $merchant_info = Merchant::find($val->merchant_id);
                        $parcel = array(
                            "merchant_info" => $merchant_info,
                            "total_parcel" => $val->total_parcel,
                            'total_cod'  => Parcel::where('merchant_id', $val->merchant_id)->whereRaw("id in ($parcel_id) ")->sum('price'),
                            'merchant_id' => $val->merchant_id,
                            'parcels' => $parcels
                        );
                        array_push($merchant_return, $parcel);
                    }
                    $val=0;
                return view('admin.parcel.bulk-return-print', compact('parcels_summary','merchant_return','val'));
                else:
                    return  back()->with('danger',__('access_denied'));
                endif;
            } catch (\Exception $e){
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            }
       else:
            return back()->with('danger', __('Sorry_parcel_not_select'));
       endif;
    }

    public function marchantReturnModal(Request $request)
    {
        $parcel = Parcel::findOrfail($request->parcel_id);
        $return_sms = SmsTemplate::where('subject','parcel_otp_event')->first();
        $output =  view('admin.parcel.modal.returned-to-merchant-modal', compact('parcel', 'return_sms'));
        return $output;
    }

    public function deliveryModal(Request $request)
    {
           $parcel = Parcel::findOrfail($request->parcel_id);
           $sms_info = '';
           $otp_permission  = Setting::where('title', "delivery_otp")->first();

            if(empty($parcel->otp) &&  $parcel->status =='delivery-assigned' && ($otp_permission->value == 'all' || $otp_permission->value == 'conditional')):
                if($otp_permission->value == trim('all')):
                        $all_parcel = CustomerParcelSmsTemplates::where('subject', "customer_parcel_delivery_otp_event")->first();
                        $sms_info = $this->deliveryOtp($parcel->id, $all_parcel->subject); //otp send for all delivery

                elseif($otp_permission->value == trim('conditional')):

                    if($parcel->price <=  $parcel->total_delivery_charge):

                        $cod_parcel = CustomerParcelSmsTemplates::where('subject', "customer_parcel_delivery_otp_cod_event")->first();
                        $sms_info = $this->deliveryOtp($parcel->id, $cod_parcel->subject); //otp send for less cod

                    else:
                        $sms_info = '';
                    endif;
                else:
                    $sms_info = '';
                endif;

            elseif($otp_permission->value == 'all' || $otp_permission->value == 'conditional'):
                $sms_info = $parcel->customer_phone_number;

            else:
                $sms_info = '';
            endif;


           $output = view('admin.parcel.modal.parcel-delivery-modal', compact('parcel', 'sms_info'));
           return $output;


    }


    public function deliveryOtp($parcel_id, $title)
    {

         $otp = $this->parcels->deliveryOtpGenerate($parcel_id, $title);
         if($otp['success'] == true){
            return $otp['phone'];

         }else{
            return "OTP code send fail please try again";
         }
    }

    public function deliveryOtpRequest(Request $request)
    {
        $parcel = Parcel::findOrfail($request->parcel_id);
         $all_parcel = CustomerParcelSmsTemplates::where('subject', "customer_parcel_delivery_otp_event")->first();

        if($all_parcel->sms_to_customer){
             $sms_info = $this->deliveryOtp($parcel->id, $all_parcel->subject); //otp send for all delivery
        }else if($parcel->price <=  $parcel->total_delivery_charge) {
             $cod_parcel = CustomerParcelSmsTemplates::where('subject', "customer_parcel_delivery_otp_cod_event")->first();
             if($cod_parcel->sms_to_customer){
                 $sms_info = $this->deliveryOtp($parcel->id, $cod_parcel->subject); //otp send for less cod
             }else{
                 $sms_info='';
            }
        }else{
             $sms_info='';
        }

        return $sms_info;

    }


    public function exportParcel(Request $request)
    {
        $selectd_parcels = $request->parcel_id;
        if(!empty($selectd_parcels)):
            $selectd_parcels_id =  implode(",", $selectd_parcels);
            $parcels  = Parcel::whereRaw("id in ($selectd_parcels_id) ")->get();

             $file_name = 'ParcelsDetails '.date('Y-m-d-s').'.xlsx';
             return Excel::download(new ExportParcel($parcels), $file_name);

        else:
            return back()->with('danger', __('Sorry_parcel_not_select'));

        endif;
    }


    public function assignReverse(Request $request)
    {

        $parcel = $this->parcels->get($request->id);
        $parcel_info = ParcelDeliver::where('parcel_no', $parcel->parcel_no)->first();
        if(!empty($parcel_info)):
            $parcel_info->status = "reversed";
            $parcel_info->save();

            if($this->deliveryReverse($request)):
                return true;
            else:
                return false;
            endif;
        else:
            return true;
        endif;
    }


    public function deliveryOtpCheck(Request $request)
    {
         $parcel_id = $request->parcel_id;
         $parcel = $this->parcels->get($parcel_id);
         $otp_code = $request->otp_code;
         if($parcel->otp == $otp_code)
         {
            return true;
         }else{
            return false;
         }
    }


}
