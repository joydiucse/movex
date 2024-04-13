<?php

namespace App\Repositories;

use App\Models\BulkDelivery;
use App\Models\CustomerParcelSmsTemplates;
use App\Models\DeliveryMan;
use App\Models\Parcel;
use App\Models\ParcelEvent;
use App\Repositories\Interfaces\BulkInterface;
use App\Traits\SmsSenderTrait;
use App\Models\ParcelReturn;
use App\Models\BulkReturn;
use App\Models\Merchant;
use App\Models\ParcelDeliver;
use Sentinel;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Catch_;
use App\Repositories\Interfaces\AccountInterface;
class BulkRepository implements BulkInterface {

    use SmsSenderTrait;
    protected $accounts;

    public function __construct(AccountInterface $accounts)
    {
        $this->accounts     = $accounts;

    }

    public function get($id)
    {
        return Parcel::find($id);
    }

    public function bulkAssign($data)
    {

        if(isset($data->batch_no)):
            $batch_no = $data->batch_no;
        else:
            $batch_no = $this->createDeliveryBatch($data['delivery_man']);
        endif;
        DB::beginTransaction();
        try {
            if(!empty($batch_no)):
                foreach ($data['parcel_list'] as $parcel_id):
                    $parcel                     = $this->get($parcel_id);
                    if($this->parcelDelivery($parcel->parcel_no, $data['delivery_man'], $batch_no)): //delivery assign list
                        $previous_status            = $parcel->status;
                        if($previous_status !== 'delivery-assigned'):
                            if ($previous_status == 'received' || $previous_status == 'transferred-received-by-hub' ||
                                $previous_status == 'delivery-assigned' || $previous_status == 're-schedule-delivery'):
                                $parcel->status             = ($previous_status == 'received' || $previous_status == 'transferred-received-by-hub' || $previous_status == 're-schedule-delivery') ? 'delivery-assigned' : 're-schedule-delivery';
                                $parcel->delivery_man_id    = $data['delivery_man'];
                                $parcel->delivery_fee       = DeliveryMan::find($data['delivery_man'])->delivery_fee;
                                $parcel->save();

                                $parcel_event                   = new ParcelEvent();
                                $parcel_event->parcel_id        = $parcel_id;
                                $parcel_event->delivery_man_id  = $data['delivery_man'];
                                $parcel_event->pickup_man_id    = $parcel->pickup_man_id;
                                $parcel_event->user_id          = \Sentinel::getUser()->id;
                                $parcel_event->cancel_note      = ($previous_status == 'received' || $previous_status == 'transferred-received-by-hub' || $previous_status == 're-schedule-delivery') ? '': __('delivery_man_changed');
                                $parcel_event->title            = ($previous_status == 'received' || $previous_status == 'transferred-received-by-hub' || $previous_status == 're-schedule-delivery') ? 'assign_delivery_man_event' : 'parcel_re_schedule_delivery_event';
                                $parcel_event->save();
                            endif;
                        endif;
                    endif;
                endforeach;
                if ($data['notify_customer'] == 'notify'):
                    $this->parcelEvent($parcel, 'assign_delivery_man_event', $data['delivery_man'], '', '');
                endif;

            else:
                return false;
            endif;

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function bulkTransferSave($data)
    {
        DB::beginTransaction();
        try {
            foreach ($data['parcel_list'] as $parcel_id):

                $parcel                             = $this->get($parcel_id);
                $parcel->status                     = 'transferred-to-hub';
                $parcel->transfer_to_hub_id         = $data['hub'];
                $parcel->transfer_delivery_man_id   = $data['delivery_man'];
                $parcel->save();

                $parcel_event                           = new ParcelEvent();
                $parcel_event->parcel_id                = $parcel_id;
                $parcel_event->pickup_man_id            = $parcel->pickup_man_id;
                $parcel_event->delivery_man_id          = $parcel->delivery_man_id;
                $parcel_event->transfer_delivery_man_id = $data['delivery_man'];
                $parcel_event->user_id                  = \Sentinel::getUser()->id;
                $parcel_event->hub_id                   = $data['hub'];
                $parcel_event->title                    = 'parcel_transferred_to_hub_assigned_event';
                $parcel_event->save();

            endforeach;

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function bulkTransferReceive($data)
    {
        DB::beginTransaction();
        try {
            foreach ($data['parcel_list'] as $parcel_id):

                $parcel                             = $this->get($parcel_id);
                $parcel->status                     = 'transferred-received-by-hub';
                $parcel->hub_id                     = $parcel->transfer_to_hub_id;
                $parcel->transfer_delivery_man_id   = $data['delivery_man'];
                $parcel->save();

                $parcel_event                           = new ParcelEvent();
                $parcel_event->parcel_id                = $parcel_id;
                $parcel_event->pickup_man_id            = $parcel->pickup_man_id;
                $parcel_event->delivery_man_id          = $parcel->delivery_man_id;
                $parcel_event->transfer_delivery_man_id = $data['delivery_man'];
                $parcel_event->user_id                  = \Sentinel::getUser()->id;
                $parcel_event->hub_id                   = $parcel->transfer_to_hub_id;
                $parcel_event->title                    = 'parcel_transferred_to_hub_event';
                $parcel_event->save();

            endforeach;

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
    public function parcelEvent($parcel, $title, $delivery_man = '', $pickup_man = '', $return_delivery_man = '', $cancel_note = '')
    {
        if (($parcel->location != 'outside_dhaka' && $title != 'assign_delivery_man_event') || ($parcel->location != 'sub_city' && $title != 'assign_delivery_man_event')):

            $delivery_person = DeliveryMan::where('id',$parcel->delivery_man_id)->first();

            //customer sms start
            $customer_sms_template = CustomerParcelSmsTemplates::where('subject',$title)->first();
            $sms_body = str_replace('{merchant_name}', $parcel->merchant->company, $customer_sms_template->content);
            $sms_body = str_replace('{parcel_id}', $parcel->parcel_no, $sms_body);
            $sms_body = str_replace('{delivery_date_time}', date('M d, Y', strtotime($parcel->delivery_date)), $sms_body);
            $sms_body = str_replace('{current_date_time}', date('M d, Y h:i a'), $sms_body);
            $sms_body = str_replace('{our_company_name}', __('app_name'), $sms_body);
            $sms_body = str_replace('{delivery_man_name}', @$delivery_person->user->first_name, $sms_body);
            $sms_body = str_replace('{delivery_man_phone}', @$delivery_person->phone_number, $sms_body);
            $sms_body = str_replace('{price}', @$parcel->price, $sms_body);

            $this->smsSender($title, $parcel->customer_phone_number, $sms_body, $customer_sms_template->masking);
            //customer sms end
        endif;
    }

    public function bulkPickupAssign($data)
    {
        DB::beginTransaction();
        try {
            foreach ($data['parcels'] as $parcel_id):

                $parcel                                = $this->get($parcel_id);
                if ($parcel->status == 'pending'):
                    $parcel->pickup_man_id             = $data['pickup_man'];
                    $parcel->status                    = 'pickup-assigned';
                    $parcel->save();

                    $parcel_event                      = new ParcelEvent();
                    $parcel_event->parcel_id           = $parcel_id;
                    $parcel_event->pickup_man_id       = $parcel->pickup_man_id;
                    $parcel_event->user_id             = \Sentinel::getUser()->id;
                    $parcel_event->title               = 'assign_pickup_man_event';
                    $parcel_event->save();
                else:
                    continue;
                endif;

            endforeach;

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }



    public function returnAssign($request)
    {
        $parcel_list =  $request->parcel_list;

        if($this->bulkReturn($request)):
            if(count($parcel_list) > 0){
                DB::beginTransaction();

                try{

                    foreach($parcel_list as $single_parcel_id){
                        $parcel_info = $this->get($single_parcel_id);
                        $return_parcel= ParcelReturn::where([
                            ['parcel_no', "=", $parcel_info->parcel_no],
                            ['status', "=", "return-assigned-to-merchant"],
                            ['batch_no', "=", $request->batch_no]
                        ])->first();
                        if($return_parcel){
                            $return_parcel->return_man_id = $request->return_man;
                            $return_parcel->save();

                        }else{
                            $parcel_return = new ParcelReturn;
                            $parcel_return->parcel_no = $parcel_info->parcel_no;
                            $parcel_return->merchant_id  = $request->merchant;
                            $parcel_return->return_man_id  = $request->return_man;
                            $parcel_return->user_id  = \Sentinel::getUser()->id;
                            $parcel_return->status = "return-assigned-to-merchant";
                            $parcel_return->batch_no  = $request->batch_no;
                            $parcel_return->save();
                        }

                    }

                    $bulk_reutn = bulkReturn::where('batch_no', $request->batch_no)->where('merchant_id', $request->merchant)->first();
                    $bulk_reutn->delivery_man_id= $request->return_man;
                    $bulk_reutn->save();
                    DB::commit();
                    return true;
                }catch(Exception $e){
                    DB::rollback();
                    return false;
                }
            }
        endif;
    }

    public function bulkReturn($data)
    {
       if($this->previousOtpCheck($data)){
            DB::beginTransaction();
            try {
                foreach ($data['parcel_list'] as $parcel_id):

                    $parcel                     = $this->get($parcel_id);
                    $previous_status            = $parcel->status;

                    if($previous_status == 'return-assigned-to-merchant'){
                        $parcel->return_delivery_man_id    = $data['return_man'];
                        $parcel->save();

                        $parcel_event                   = ParcelEvent::where('parcel_id', $parcel_id)->orderByDesc('id')->first();
                        $parcel_event->user_id          = \Sentinel::getUser()->id;
                        $parcel_event->title            = "parcel_return_assign_to_merchant_event";
                        if($parcel_event->return_delivery_man_id != $data['return_man']):
                            $parcel_event->cancel_note      = "Return man Changed";
                            $parcel_event->return_delivery_man_id  = $data['return_man'];
                        endif;
                        $parcel_event->save();
                        
                    }else if ($previous_status == 'returned-to-greenx'){
                        $parcel->status             = 'return-assigned-to-merchant';
                        $parcel->return_delivery_man_id    = $data['return_man'];
                        $parcel->return_fee       = DeliveryMan::find($data['return_man'])->return_fee;
                        $parcel->date = date('Y-m-d');
                        $parcel->save();
                        $this->accounts->incomeExpenseManage($parcel->id, 'return-assigned-to-merchant');

                        $parcel_event                   = new ParcelEvent();
                        $parcel_event->parcel_id        = $parcel_id;
                        $parcel_event->return_delivery_man_id  = $data['return_man'];
                        $parcel_event->user_id          = \Sentinel::getUser()->id;
                        $parcel_event->title            = "parcel_return_assign_to_merchant_event";
                        $parcel_event->save();
                    }

                   


                endforeach;

                // if ($data['notify_customer'] == 'notify'):
                //     $this->parcelEvent($parcel, 'assign_delivery_man_event', $data['return_man'], '', '');
                // endif;

                DB::commit();
                return true;

            } catch (\Exception $e) {
                DB::rollback();
                return false;
            }
        }else{
            return false;
        }
    }

    public function previousOtpCheck($data)
    {
        $parcel_id_array = array();
        if(isset($data['parcel_list'])){
            foreach($data['parcel_list'] as $parcel_id)
            {
                array_push($parcel_id_array, $parcel_id);
            }
            $parcels_id = implode(",", $parcel_id_array);
            $oldOtp = Parcel::whereIn('id', [$parcels_id])->where('merchant_otp', '<>', '')->count();
            //dd($oldOtp);
            if($oldOtp > 0){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }


    }



    public function returnedit($batch_no)
    {
       return $returnParcls= DB::table('parcel_returns as pr')
        ->selectRaw('m.company,  pr.merchant_id, p.parcel_no, m.address, u.first_name, u.last_name, p.customer_invoice_no, p.id, p.status, p.is_partially_delivered, p.customer_name, p.customer_phone_number')
        ->join('merchants as m', 'm.id', 'pr.merchant_id')
        ->join('delivery_men as dm', 'dm.id', 'pr.return_man_id')
        ->join('users as u', 'u.id', 'dm.user_id')
        ->join('parcels as p', 'p.parcel_no', 'pr.parcel_no')
        ->join('bulk_returns as br', 'br.batch_no', 'pr.batch_no')
        ->where('pr.batch_no', $batch_no)
        ->where('pr.status' , "!=", "reversed")
        ->get();
    }

    public function bulkReturnUpdate($request)
    {
        return $this->returnAssign($request);

    }

    public function creatReturnBatchStore($request)
    {

       $merchant_id = $request->merchant;
       $delivery_man_id = $request->delivery_man;
       $merchant = Merchant::select('company')->find($merchant_id);
       $delivery = DeliveryMan::find($delivery_man_id);
       $user_id          = \Sentinel::getUser()->id;
       $randnum  = rand(100000, 999999);
       $merchant_name =  substr($merchant->company, 0, 2);
       $delivery_name =  substr($delivery->user->first_name, 0, 2);
       $batch_no  = strtoupper($merchant_name).$randnum.strtoupper($delivery_name);
        DB::beginTransaction();
       try{
            $bulkReturn = new BulkReturn;
            $bulkReturn->batch_no= $batch_no;
            $bulkReturn->merchant_id = $merchant_id;
            $bulkReturn->user_id = $user_id;
            $bulkReturn->delivery_man_id = $delivery_man_id;
            $bulkReturn->status = "pending";
            $bulkReturn->save();
            DB::commit();
            return true;

       }catch(Exception $e){
            DB::rollBack();
            return false;
       }


    }


    public function createDeliveryBatch($delivery_man_id)
    {

            $delivery = DeliveryMan::find($delivery_man_id);
            $today = date('Y-m-d');
            $bulk_delivery_info = BulkDelivery::where('assign_date',$today)->where("delivery_man_id", $delivery_man_id)->first();
            if(empty($bulk_delivery_info->batch_no) or $bulk_delivery_info->status == 'processed'):
                $randnum  = rand(100000, 999999);
                $delivery_name =  substr($delivery->user->first_name, 0, 2);
                $batch_no  = $randnum.strtoupper($delivery_name);
                DB::beginTransaction();
                try{
                    $bulkDelivery = new BulkDelivery();
                    $bulkDelivery->batch_no= $batch_no;
                    $bulkDelivery->user_id = \Sentinel::getUser()->id;
                    $bulkDelivery->delivery_man_id = $delivery_man_id;
                    $bulkDelivery->assign_date = $today;
                    $bulkDelivery->status = "pending";
                    //$bulk_delivery->updated_at = 'NULL';
                    $bulkDelivery->save();
                    DB::commit();
                    return $batch_no;

                    }catch(Exception $e){
                            DB::rollBack();
                            return false;
                    }
            else:
                return $bulk_delivery_info->batch_no;
            endif;
    }

    public function parcelDelivery($parcel_no, $delivery_man_id, $batch_no)
    {
        $parcel_info = ParcelDeliver::where('parcel_no', $parcel_no)->first();
        DB::beginTransaction();

        try{
            if(empty($parcel_info->parcel_no)):
                $parcel_delivery = new ParcelDeliver();
                $parcel_delivery->parcel_no= $parcel_no;
                $parcel_delivery->delivery_man_id = $delivery_man_id;
                $parcel_delivery->user_id = \Sentinel::getUser()->id;
                $parcel_delivery->assign_date = date('Y-m-d');
                $parcel_delivery->status = "delivery-assigned";
                $parcel_delivery->batch_no = $batch_no;
                $parcel_delivery->save();
                DB::commit();
                return true;
            else:
                $parcel_info->status = "delivery-assigned";
                $parcel_info->batch_no = $batch_no;
                $parcel_info->save();
                DB::commit();
                return true;
            endif;
        }catch(Exception $e){
            DB::rollBack();
            return false;
        }
    }


    public function deliveryAssignList()
    {
        return $bulk_delivery = BulkDelivery::orderbydesc('id')->where('user_id', Sentinel::getUser()->id)->paginate(\Config::get('greenx.paginate'));

    }

    public function assignList($batch_no)
    {
        return  $assign_parcel = ParcelDeliver::where('batch_no', $batch_no)->where('status' , '<>', 'reversed')->orderbydesc('id')->paginate(\Config::get('greenx.paginate'));

    }

    public function deliveryAssignAdd($request)
    {
         $batch_no = $request->batch_no;
         if(isset($request->submit)):
            DB::beginTransaction();
             try{
                $bulk_delivery = BulkDelivery::where('batch_no', $batch_no)->first();
                $bulk_delivery->status = 'processed';
                $bulk_delivery->processed_by = \Sentinel::getUser()->id;
                $bulk_delivery->updated_at = date('Y-m-d H:i:s');
                $bulk_delivery->save();
                DB::commit();
                return $this->bulkAssign($request);
             }catch(Exception $e){
                DB::rollback();
                return false;
             }
         else:
            return $this->bulkAssign($request);
         endif;
    }
}
