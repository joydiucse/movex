<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Bulk\DeliveryAssignRequest;
use App\Http\Requests\Admin\BulkPickupAssign;
use App\Models\DeliveryMan;
use App\Models\Hub;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Repositories\BulkRepository;
use App\Repositories\Interfaces\BulkInterface;
use Illuminate\Http\Request;
use Sentinel;
use App\Models\ParcelReturn;
use App\Models\BulkReturn;
use DB;
use App\Models\BulkDelivery;
use App\Models\SmsTemplate;
class BulkController extends Controller
{
    protected $assign;

    public function __construct(BulkInterface $assign){
        $this->assign       = $assign;
    }
    public function create()
    {
        return view('admin.bulk.create');
    }

    public function save(DeliveryAssignRequest $request)
    {
        if($this->assign->bulkAssign($request)):
            $parcels = [];
            $delivery_man = DeliveryMan::find($request['delivery_man']);
            $delivery_man = $delivery_man->user->first_name.' '.$delivery_man->user->last_name;
            foreach ($request['parcel_list'] as $parcel_id):
                $parcels[] = $this->assign->get($parcel_id);
            endforeach;
            return view('admin.bulk.print', compact('parcels', 'delivery_man'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function add($parcel_no , Request $request)
    {
        $val = $request->val;
        $parcel = Parcel::where('parcel_no', $parcel_no)->whereIn('status',['received','transferred-received-by-hub','delivery-assigned','re-schedule-delivery'])->latest()->first();

        if(!blank($parcel)):
            if ($parcel->hub_id != Sentinel::getUser()->hub_id):
                return response()->json(['error' => true, 'message' => __('this_parcel_is_not_in_your_hub')]);
            endif;
            $view = view('admin.bulk.new-parcel-row', compact('parcel','val'))->render();
            return response()->json(['val' => $val, 'view' => $view]);
        endif;
    }

    public function bulkTransferCreate()
    {
        $hubs = Hub::where('user_id','!=', Sentinel::getUser()->id)->get();
        return view('admin.bulk.hub-transfer',compact('hubs'));
    }

    public function transferAdd($parcel_no , Request $request)
    {
        $val = $request->val;
        $parcel = Parcel::where('parcel_no', $parcel_no)->whereIn('status',['received','transferred-received-by-hub'])->latest()->first();

        if ($parcel->hub_id != Sentinel::getUser()->hub_id):
            return response()->json(['error' => true, 'message' => __('this_parcel_is_not_in_your_hub')]);
        endif;

        if(!blank($parcel)):
            $view = view('admin.bulk.new-parcel-row', compact('parcel','val'))->render();
            return response()->json(['val' => $val, 'view' => $view]);
        endif;
    }

    public function bulkTransferSave(DeliveryAssignRequest $request)
    {
        if($this->assign->bulkTransferSave($request)):
            $parcels = [];
            $delivery_man = DeliveryMan::find($request['delivery_man']);
            $delivery_man = $delivery_man->user->first_name.' '.$delivery_man->user->last_name;
            foreach ($request['parcel_list'] as $parcel_id):
                $parcels[] = $this->assign->get($parcel_id);
            endforeach;
            return view('admin.bulk.print', compact('parcels', 'delivery_man'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }
    public function bulkTransferReceive()
    {
        $hubs = Hub::where('user_id','!=', Sentinel::getUser()->id)->get();
        return view('admin.bulk.transfer-receive',compact('hubs'));
    }

    public function transferReceive($parcel_no , Request $request)
    {
        $val = $request->val;
        $parcel = Parcel::where('parcel_no', $parcel_no)->where('status','transferred-to-hub')->latest()->first();

        if ($parcel->transfer_to_hub_id != Sentinel::getUser()->hub_id):
            return response()->json(['error' => true, 'message' => __('this_parcel_is_not_transferred_to_your_hub')]);
        endif;

        if(!blank($parcel)):
            $view = view('admin.bulk.new-parcel-row', compact('parcel','val'))->render();
            return response()->json(['val' => $val, 'view' => $view]);
        endif;
    }

    public function bulkTransferReceivePost(DeliveryAssignRequest $request)
    {
        if($this->assign->bulkTransferReceive($request)):
            $parcels = [];
            $delivery_man = DeliveryMan::find($request['delivery_man']);
            $delivery_man = $delivery_man->user->first_name.' '.$delivery_man->user->last_name;
            foreach ($request['parcel_list'] as $parcel_id):
                $parcels[] = $this->assign->get($parcel_id);
            endforeach;
            $receive = 'receive';
            return view('admin.bulk.print', compact('parcels', 'delivery_man', 'receive'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function createPickup()
    {
        return view('admin.bulk.pickup');
    }

    public function getParcels(Request $request)
    {
        $merchant = Merchant::find($request->merchant);

        if (blank($merchant)):
            return response()->json(['error' => true, 'message' => __('merchant_not_found')]);
        endif;

        $parcels = $merchant->parcels()->where('status', 'pending')->get();

        if(!blank($parcels)):
            $view = view('admin.bulk.parcels', compact('parcels'))->render();
            return response()->json(['view' => $view]);
        else:
            return response()->json(['error' => true, 'message' => __('no_parcel_found_for_this_merchant')]);
        endif;
    }

    public function bulkPickupAssign(BulkPickupAssign $request)
    {
        if($this->assign->bulkPickupAssign($request)):
            $parcels = [];
            foreach ($request['parcels'] as $parcel_id):
                $parcels[] = $this->assign->get($parcel_id);
            endforeach;

            return back()->with('success', __('pickup_assigned_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function bulkReturn()
    {
        return view('admin.bulk.return');
    }

    public function returnAdd($parcel_no , Request $request)
    {
        $val = $request->val;
        $merchant_id = $request->merchant;
        $assign_parcel = ParcelReturn::where('parcel_no', $parcel_no)->where('status', '<>', 'reversed')->get();

        if(count($assign_parcel) == 0):
            $parcel = Parcel::where('parcel_no', $parcel_no)->whereIn('status',['returned-to-greenx', 'return-assigned-to-merchant'])->where('merchant_id', $merchant_id)->latest()->first();

            if(!blank($parcel)):
                $view = view('admin.bulk.return-parcel-row', compact('parcel','val'))->render();
                return response()->json(['val' => $val, 'view' => $view]);
            endif;
        else:
            $data = [
               'error' => true,
               'message' => "Sorry !! Parcel not available for this return or already added"
            ];
            return $data;
       endif;
    }

    public function returnSave(Request $request)
    {
       $assign_parcel = ParcelReturn::where($request->merchant_id)->where('status', "return-assigned-to-merchant")->first();

       if(!$assign_parcel){
        if($this->assign->returnAssign($request)):
            $parcels = [];
            $delivery_man = DeliveryMan::find($request->return_man);
            $delivery_man = $delivery_man->user->first_name.' '.$delivery_man->user->last_name;
            $merchant_man = Merchant::find($request->merchant);
            $merchant_name = $merchant_man->user->first_name.' '.$merchant_man->user->last_name;
            foreach ($request['parcel_list'] as $parcel_id):
                $parcels[] = $this->assign->get($parcel_id);
            endforeach;
            return view('admin.bulk.return-print', compact('parcels', 'delivery_man', 'merchant_name'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
       }else{
            return back()->with('danger', __('bulk_merchant_return_add'));
       }


    }

    public function returnedit($batch_no)
    {

        $bulk_return = bulkReturn::where('batch_no', $batch_no)->first();
        $return_list = $this->assign->returnedit($batch_no);
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

        return view('admin.bulk.return_edit', $data);
    }

    public function bulkReturnUpdate(Request $request)
    {
        if ($this->bulkstatusCheck($request)):
            if(hasPermission('parcel_return_assigned_to_merchant')):
                if($request->print_list == "print"){
                    $parcels = [];
                    $delivery_man = DeliveryMan::find($request->return_man);
                    $delivery_man = $delivery_man->user->first_name.' '.$delivery_man->user->last_name;
                    $merchant_man = Merchant::find($request->merchant);
                    $merchant_name = $merchant_man->user->first_name.' '.$merchant_man->user->last_name;
                        if($this->bulkReturnOtpCheck($request)== true && $this->assign->bulkReturnUpdate($request)):
                            foreach ($request['parcel_list'] as $parcel_id):
                                $parcels[] = $this->assign->get($parcel_id);
                            endforeach;
                            return view('admin.bulk.return-print', compact('parcels', 'delivery_man', 'merchant_name'));
                        else:
                            $assign_parcels = ParcelReturn::where('batch_no', $request->batch_no)->where('status', 'return-assigned-to-merchant')->get();
                            foreach ($assign_parcels as $parcel):
                                $parcel_info= Parcel::where('parcel_no', $parcel->parcel_no)->select('id')->first();
                                $parcels[] = $this->assign->get($parcel_info->id);
                            endforeach;
                            return view('admin.bulk.return-print', compact('parcels', 'delivery_man', 'merchant_name'));
                        endif;

                }else{
                    if($this->bulkReturnOtpCheck($request)== true && $this->assign->bulkReturnUpdate($request)){
                        return redirect()->route('parcel.return.list')->with('success', __('parcel_add_successful'));
                    }else{
                        return back()->with('danger', __('sorry_otp_already_sent_you_can_not_add_parcel_to_this_bulk'));
                    }
                }
            endif;
        else:
            return back()->with('danger', __('sorry_parcel_already_returned_or_status_not_allowed'));
        endif;

    }

    public function bulkReturnOtpCheck($data)
    {
        $parcel_id_array = array();
        if(isset($data['parcel_list'])){
            foreach($data['parcel_list'] as $parcel_id)
            {
                array_push($parcel_id_array, $parcel_id);
            }
            $parcels_id = implode(",", $parcel_id_array);
            $oldOtp = Parcel::whereIn('id', [$parcels_id])->where('merchant_otp', '<>', '')->count();
            if($oldOtp > 0){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }


    }


    public function creatReturnBatch()
    {
        return view('admin.parcel.return.create-return-batch');
    }

    public function creatReturnBatchStore(Request $request)
    {
        $validation  = $this->validate($request, [
            'merchant' => "required",
            'delivery_man' =>'required'
        ]);

        if(hasPermission('parcel_return_assigned_to_merchant')):
            if($this->assign->creatReturnBatchStore($request)){
                return redirect()->route('parcel.return.list')->with('success', __('return_batch_create_success'));
            }else{
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            }
        endif;
    }



    public function deliveryAssignList()
    {
        $assign_list = $this->assign->deliveryAssignList();
        $val = 1;
        return view('admin.bulk.delivery-assign.index', compact('assign_list', 'val'));
    }

    public function assignList($batch_no)
    {

        $bulk_delivery = BulkDelivery::where('batch_no', $batch_no)->first();
        $assign_parcel =  $this->assign->assignList($batch_no);
        $delivery_man = DeliveryMan::find($bulk_delivery->delivery_man_id);

        $val= 1;
        $data = [
            'bulk_delivery' => $bulk_delivery,
            'val' => 1,
            "delivery_man" => $delivery_man,
            "batch_no" => $batch_no,
            'assign_parcel' => $assign_parcel
        ];
        return view('admin.bulk.delivery-assign.assign-parcel', $data);
    }

    public function deliveryAssignAdd(Request $request)
    {  
        if($this->deliveryAssignSatusCheck($request)):
            if($this->assign->deliveryAssignAdd($request)):
                $parcels = [];
                $delivery_man = DeliveryMan::find($request['delivery_man']);
                $delivery_man = $delivery_man->user->first_name.' '.$delivery_man->user->last_name;
                foreach ($request['parcel_list'] as $parcel_id):
                    $parcels[] = $this->assign->get($parcel_id);
                endforeach;
                return view('admin.bulk.print', compact('parcels', 'delivery_man'));
            else:
                return back()->with('danger', __('something_went_wrong_please_try_again'));
            endif;
        else:
            return back()->with('danger', __('sorry_parcel_already_assigned_or_status_not_allowed'));
        endif;
    }

    public function deliveryAssignPrint(Request $request)
    {
            $parcels = [];
            $delivery_man = DeliveryMan::find($request['delivery_man']);
            $delivery_man = $delivery_man->user->first_name.' '.$delivery_man->user->last_name;
            foreach ($request['parcel_list'] as $parcel_id):
                $parcels[] = $this->assign->get($parcel_id);
            endforeach;
            return view('admin.bulk.print', compact('parcels', 'delivery_man'));
    }



    public function bulkstatusCheck($request)
    {
        $parcels = Parcel::whereIn('id', $request['parcel_list'])->whereIn('status', ['returned-to-greenx','return-assigned-to-merchant'])->count();
        if($parcels == count($request['parcel_list'])):
            return true;
        else:
            return false;
        endif;
    }

    public function deliveryAssignSatusCheck($request)
    {
        
        $parcels = Parcel::whereIn('id', $request['parcel_list'])->whereIn('status', ['received','transferred-received-by-hub', 'delivery-assigned', 're-schedule-delivery'])->count();
        if($parcels == count($request['parcel_list'])):
            return true;
        else:
            return false;
        endif;
    }
}
