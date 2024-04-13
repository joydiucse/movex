<?php

namespace App\Http\Controllers\Api\DeliveryMan\V13;

use App\Http\Controllers\Controller;
use App\Models\CustomerParcelSmsTemplates;
use App\Models\DeliveryMan;
use App\Models\Parcel;
use App\Models\ParcelEvent;
use App\Models\SmsTemplate;
use App\Repositories\Interfaces\AccountInterface;
use App\Repositories\Interfaces\ParcelInterface;
use App\Traits\ApiReturnFormatTrait;
use App\Traits\SmsSenderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use DB;
use App\Models\Setting;
use App\Models\BulkReturn;
use App\Models\ParcelReturn;
class ParcelController extends Controller
{
    use ApiReturnFormatTrait;
    use SmsSenderTrait;

    protected $parcels;
    protected $accounts;

    public function __construct(ParcelInterface $parcels, AccountInterface $accounts)
    {
        $this->parcels          = $parcels;
        $this->accounts          = $accounts;
    }

    public function myPickup(Request $request)
    {
        try{
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $page = $request->page ?? 1;

            $offset = ( $page * \Config::get('greenx.api_paginate') ) - \Config::get('greenx.api_paginate');
            $limit  = \Config::get('greenx.api_paginate');

            $my_pickup = Parcel::with('merchant','pickupMan','deliveryMan')->where('pickup_man_id', $user->deliveryMan->id)->whereIn('status', ['pickup-assigned','re-schedule-pickup'])->orderByDesc('id')->latest()->skip($offset)->take($limit)->get();

            $data = $this->parcelListReturnFormat($my_pickup);

            return $this->responseWithSuccess(__('successfully_found'),$data ,200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong'), [], 500);
        }
    }

    public function pickupPending(Request $request)
    {
        try{
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $page = $request->page ?? 1;

            $offset = ( $page * \Config::get('greenx.api_paginate') ) - \Config::get('greenx.api_paginate');
            $limit  = \Config::get('greenx.api_paginate');

            $pending   = Parcel::with('merchant','pickupMan','deliveryMan')->where('status', 'pending')->orderByDesc('id')->latest()->skip($offset)->take($limit)->get();
            $data   = $this->parcelListReturnFormat($pending);

            return $this->responseWithSuccess(__('successfully_found'),$data ,200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong'), [], 500);
        }
    }

    public function pickupCompleted(Request $request)
    {
        try{
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $page = $request->page ?? 1;

            $offset = ( $page * \Config::get('greenx.api_paginate') ) - \Config::get('greenx.api_paginate');
            $limit  = \Config::get('greenx.api_paginate');

            $completed = Parcel::with('merchant','pickupMan','deliveryMan')->where('pickup_man_id', $user->deliveryMan->id)->whereNotIn('status', ['pending','pickup-assigned','re-schedule-pickup'])->orderByDesc('id')->latest()->skip($offset)->take($limit)->get();

            $data = $this->parcelListReturnFormat($completed);

            return $this->responseWithSuccess(__('successfully_found'),$data ,200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong'), [], 500);
        }
    }

    public function myDelivery(Request $request)
    {
        try{
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }
            $page = $request->page ?? 1;

            $offset = ( $page * \Config::get('greenx.api_paginate') ) - \Config::get('greenx.api_paginate');
            $limit  = \Config::get('greenx.api_paginate');

            $my_delivery    = Parcel::with('merchant','pickupMan.user','deliveryMan.user')
                ->where('delivery_man_id', $user->deliveryMan->id)
                ->where(function ($q){
                    $q->where('status','delivery-assigned');
                    $q->orWhere(function ($inner){
                        $inner->where('status', 're-schedule-delivery');
                        $inner->where('delivery_date',date('Y-m-d'));
                    });
                })
                ->orderByDesc('id')
                ->latest()
                ->skip($offset)
                ->take($limit)
                ->get();

            $data = $this->parcelListReturnFormat($my_delivery);

            return $this->responseWithSuccess(__('successfully_found'),$data ,200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong'), [], 500);
        }

    }

    public function myReScheduledDelivery(Request $request)
    {
        try{
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }
            $page = $request->page ?? 1;

            $offset = ( $page * \Config::get('greenx.api_paginate') ) - \Config::get('greenx.api_paginate');
            $limit  = \Config::get('greenx.api_paginate');

            $my_delivery    = Parcel::with('merchant','pickupMan.user','deliveryMan.user')
                ->where('delivery_man_id', $user->deliveryMan->id)
                ->where('status', 're-schedule-delivery')
                ->orderByDesc('id')
                ->latest()
                ->skip($offset)
                ->take($limit)
                ->get();

            $data = $this->parcelListReturnFormat($my_delivery, true);

            return $this->responseWithSuccess(__('successfully_found'),$data ,200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong'), [], 500);
        }

    }

    public function deliveryPending(Request $request)
    {
        try{
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $page = $request->page ?? 1;

            $offset = ( $page * \Config::get('greenx.api_paginate') ) - \Config::get('greenx.api_paginate');
            $limit  = \Config::get('greenx.api_paginate');

            $pending        = Parcel::with('merchant','pickupMan.user','deliveryMan.user')->where('status', 'received')->orderByDesc('id')->latest()->skip($offset)->take($limit)->get();

            $data = $this->parcelListReturnFormat($pending);

            return $this->responseWithSuccess(__('successfully_found'),$data ,200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong'), [], 500);
        }
    }

    public function deliveryCompleted(Request $request)
    {
        try{
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $page = $request->page ?? 1;

            $offset = ( $page * \Config::get('greenx.api_paginate') ) - \Config::get('greenx.api_paginate');
            $limit  = \Config::get('greenx.api_paginate');

            $completed    = Parcel::with('merchant','pickupMan.user','deliveryMan.user')->where('delivery_man_id', $user->deliveryMan->id)->whereIn('status', ['delivered','delivered-and-verified'])->orderByDesc('id')->latest()->skip($offset)->take($limit)->get();

            $data = $this->parcelListReturnFormat($completed);

            return $this->responseWithSuccess(__('successfully_found'),$data ,200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong'), [], 500);
        }

    }

    public function parcelListReturnFormat($parcels, $reschedule_status = false)
    {
        foreach ($parcels as $parcel):
            $parcel['re_scheduled_status'] = $parcel->status == 're-schedule-delivery' ? 1 : 0;
            $parcel['parcel_no'] = $parcel->parcel_no;
            $parcel['parcel_type'] = __($parcel->parcel_type);
            $parcel['weight'] = $parcel->weight.' '.__('kg');
            $parcel['charge'] = $parcel->charge.' '.__('tk');
            $parcel['cod_charge'] = $parcel->cod_charge.' '.__('tk');
            $parcel['vat'] = $parcel->vat.' '.__('tk');
            $parcel['location'] = __($parcel->location);
            $parcel['total_delivery_charge'] = __($parcel->total_delivery_charge).' '.__('tk');
            $parcel['payable'] = __($parcel->payable).' '.__('tk');
            $parcel['price'] = __($parcel->price).' '.__('tk');
            $parcel['selling_price'] = __($parcel->selling_price).' '.__('tk');
            $parcel['merchant_company'] = $parcel->merchant->company;
            $parcel['pickup_person'] = @$parcel->pickupMan->user['first_name'].' '.@$parcel->pickupMan->user['last_name'];
            $parcel['delivery_person'] = @$parcel->deliveryMan->user['first_name'].' '.@$parcel->deliveryMan->user['last_name'];
            $parcel['status'] = __($parcel->status);
            $parcel['status_before_cancel'] = __($parcel->status_before_cancel);
            $parcel['created'] = date('M d, Y g:i A', strtotime($parcel->created_at));
            $parcel['updated'] = date('M d, Y g:i A', strtotime($parcel->created_at));
            unset($parcel->merchant_id);
            unset($parcel->pickup_man_id);
            unset($parcel->delivery_man_id);
            unset($parcel->merchant);
            unset($parcel->pickupMan);
            unset($parcel->deliveryMan);
            unset($parcel->created_at);
            unset($parcel->updated_at);
        endforeach;

        return $parcels;
    }

    public function paginationFormat($parcels)
    {
        if (isset($parcels['links'])) {
            unset($parcels['links']);
        }
        if (isset($parcels['meta'], $parcels['meta']['links'])) {
            unset($parcels['meta']['links']);
        }

        return $parcels;
    }

    public function parcelDeliveryConfirm(Request $request)
    {
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'id'    => 'required|max:50',
                'otp'   => 'required|max:50',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError('required_field_missing', $validator->errors(), 422);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $parcel = Parcel::find($request->id);

            if ($parcel->status == 'delivered-and-verified'):
                return $this->responseWithError(__('this_parcel_has_already_been_delivered_and_verified'), [], 422);
            endif;
            if ($parcel->status != 'delivered' || $parcel->status != 'partially-delivered'):
                return $this->responseWithError(__('this_parcel_yet_not_delivered'), [], 422);
            endif;

            if (isset($parcel)):
                if ($parcel->otp == $request->otp):
                    $parcel->status         = 'delivered-and-verified';
                    $parcel->date           = date('Y-m-d');
                    $parcel->save();

                    $parcel_event                      = new ParcelEvent();
                    $parcel_event->parcel_id           = $parcel->id;
                    $parcel_event->delivery_man_id     = $parcel->delivery_man_id;
                    $parcel_event->pickup_man_id       = $parcel->pickup_man_id;
                    $parcel_event->user_id             = $user->id;
                    $parcel_event->title               = 'parcel_delivered_and_verified_event';
                    $parcel_event->cancel_note         = $request->note;
                    $parcel_event->save();

                    DB::commit();

                    return $this->responseWithSuccess(__('delivery_successfully_verified'),[] ,200);
                else:
                    return $this->responseWithError(__('please_provide_correct_otp'), [] , 422);
                endif;
            else:
                return $this->responseWithError(__('parcel_not_found'), [] , 404);
            endif;
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong'), [], 500);
        }
    }

    public function reshedulePickup(Request $request)
    {
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'id'    => 'required|max:50',
                'date'     => 'required',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError('required_field_missing', $validator->errors(), 422);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }
            $stsResponse = $this->statusCheck($request->id, "re-schedule-pickup");
            if($stsResponse === true):
                $parcel                       = Parcel::find($request->id);
                $parcel->status               = 're-schedule-pickup';
                $parcel->date                 = date('Y-m-d');
                $parcel->pickup_date          = date('Y-m-d', strtotime($request->date));
                $parcel->pickup_time          = date('h:i:s', strtotime($request->time));
                $parcel->pickup_man_id        = $user->deliveryMan->id;
                $parcel->pickup_fee           = DeliveryMan::find($parcel->pickup_man_id)->pick_up_fee;
                $parcel->save();

                $this->parcelEvent($parcel->id, 'parcel_re_schedule_pickup_event',$request->note , $user->id);

                DB::commit();
                return $this->responseWithSuccess(__('successfully_re_scheduled'),[] ,200);
            else:
                return $this->responseWithError(__($stsResponse), [], 500);
            endif;

        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }

    }

    public function resheduleDelivery(Request $request)
    {
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'id'    => 'required|max:50',
                'date'     => 'required',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError('required_field_missing', $validator->errors(), 422);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }
            $stsResponse = $this->statusCheck($request->id, "re-schedule-delivery");
            if($stsResponse === true):
                $parcel                       = $this->parcels->get($request->id);
                if ($parcel->status == 're-schedule-delivery' || $parcel->status == 'delivery-assigned'):
                    $parcel->status               = 're-schedule-delivery';
                    $parcel->date                 = date('Y-m-d');
                    $parcel->delivery_date        = date('Y-m-d', strtotime($request->date));
                    $parcel->delivery_time        = date('h:i:s', strtotime($request->time));
                    $parcel->delivery_man_id      = $user->deliveryMan->id;
                    $parcel->delivery_fee         = DeliveryMan::find($parcel->delivery_man_id)->delivery_fee;
                    $parcel->save();

                    $this->parcelEvent($parcel->id, 'parcel_re_schedule_delivery_event',  $request->note, $user->id);

                    DB::commit();
                    return $this->responseWithSuccess(__('successfully_re_scheduled'),[] ,200);
                else:
                    return $this->responseWithError(__('you_cant_re_schedule_this_parcel_anymore'), [], 422);
                endif;
            else:
                return $this->responseWithError(__($stsResponse), [], 500);
            endif;

        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }

    }

    public function cancel(Request $request)
    {

        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(),[
                'id'    => 'required|max:50',
                'cancel_note'  => 'required',
            ]);

            if ($validator->fails()){
                return $this->responseWithError('required_field_missing', $validator->errors(), 422);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()){
                return $this->responseWithError(__('unauthorized_user'), '', 404);
            }
           
            $request['delivery_man'] = $user->deliveryMan->id;
            $parcel                         = Parcel::find($request->id);

            if ($parcel->status == 'cancel'):
                return $this->responseWithError(__('this_parcel_has_already_been_cancelled'), [], 422);
            endif;

            if ($parcel->status == 'received' || $parcel->status == 'delivered' || $parcel->status == 'partially-delivered' || $parcel->status == 'delivered-and-verified' || $parcel->status == 'returned-to-merchant'):
                return $this->responseWithError(__('this_parcel_can_not_be_cancelled'), [], 422);
            endif;

            $this->accounts->incomeExpenseManageCancel($request->id, 'cancel');


            $parcel->status_before_cancel   = $parcel->status;
            $parcel->status                 = 'cancel';
            $parcel->date                   = date('Y-m-d');
            $parcel->save();

            $this->parcelEvent($parcel->id, 'parcel_cancel_event', $request->cancel_note, $user->id);

            DB::commit();
            return $this->responseWithSuccess(__('successfully_cancelled'), [], 200);
            

        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function delivery(Request $request)
    {

        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(),[
                'id'    => 'required|max:50',
                'otp'   => 'required|max:50',
            ]);

            if ($validator->fails()){
                return $this->responseWithError('required_field_missing', $validator->errors(), 422);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()){
                return $this->responseWithError(__('unauthorized_user'), '', 404);
            }

            $stsResponse = $this->statusCheck($request->id, "delivered");
            if($stsResponse === true):
                $parcel                 = Parcel::find($request->id);
                if ($parcel->status == 'delivered' || $parcel->status == 'delivered-and-verified' || $parcel->status == 'partially-delivered'):
                    return $this->responseWithError(__('this_parcel_has_already_confirmed_as_delivered'), [], 422);
                endif;

                if($parcel->otp != $request->otp):
                    return $this->responseWithError(__('sorry_this_otp_not_matched_for_this_parcel'), [], 422);
                endif;
                $parcel->date           = date('Y-m-d');
                $parcel->status         = 'delivered';

                $this->accounts->incomeExpenseManage($request->id, 'delivered');

                $parcel->otp = rand(1000,9999);
                $this->parcelEvent($parcel->id, 'parcel_delivered_event', $request->note, $user->id);

                $sms_template = CustomerParcelSmsTemplates::where('subject','delivery_confirm_otp')->first();

                $sms_body = str_replace('{merchant_name}', $parcel->merchant->company, $sms_template->content);
                $sms_body = str_replace('{parcel_id}', $parcel->parcel_no, $sms_body);
                $sms_body = str_replace('{otp}', $parcel->otp, $sms_body);
                $sms_body = str_replace('{our_company_name}', __('app_name'), $sms_body);

                $parcel->save();
                if($sms_template->sms_to_customer):
                    $this->smsSender('delivery_confirm_otp', $parcel->customer_phone_number, $sms_body, $sms_template->masking);
                endif;

                DB::commit();

                return $this->responseWithSuccess(__('successfully_delivered'), [], 200);
            else:
                return $this->responseWithError(__($stsResponse), [], 500);
            endif;

        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function parcelDetails($id)
    {
        try {
            $parcel = $this->parcels->get($id);

            if (!blank($parcel)):
                $data['parcel_no'] = $parcel->parcel_no;
                $data['status'] = __($parcel->status);

                $merchant['merchan_name'] = $parcel->merchant->user->first_name . ' ' . $parcel->merchant->user->last_name;
                $merchant['merchant_company'] = $parcel->merchant->company;
                $merchant['phone_number'] = $parcel->merchant->phone_number;
                $merchant['address'] = $parcel->merchant->address;
                $merchant['email'] = $parcel->merchant->user->email;
                $merchant['created'] = date('M d, Y g:i a', strtotime($parcel->created_at));
                $merchant['parcel_type'] = __($parcel->parcel_type);
                $merchant['total_charge'] = __($parcel->total_delivery_charge) . ' ' . __('tk');
                $merchant['pickup_person'] = @$parcel->pickupMan->user['first_name'] . ' ' . @$parcel->pickupMan->user['last_name'];
                $merchant['delivery_person'] = @$parcel->deliveryMan->user['first_name'] . ' ' . @$parcel->deliveryMan->user['last_name'];
                $merchant['pickup'] = date('M d, Y', strtotime($parcel->pickup_date));
                $merchant['delivery'] = date('M d, Y', strtotime($parcel->delivery_date));
                $merchant['pickup'] = date('M d, Y', strtotime($parcel->pickup_date));

                if ($parcel->status == 'delivered' || $parcel->status == 'delivered-and-verified'):
                    $merchant['delivered_at'] = date('M d, Y g:i A', strtotime($parcel->updated_at));
                endif;

                $customer['id'] = $parcel->parcel_no;
                $customer['invno'] = $parcel->customer_invoice_no;
                $customer['customer_name'] = $parcel->customer_name;
                $customer['customer_mobile_no'] = $parcel->customer_phone_number;
                $customer['customer_mobile_no'] = $parcel->customer_address;
                $customer['location'] = __($parcel->location);
                $customer['note'] = __($parcel->note);
                $customer['weight'] = $parcel->weight . ' ' . __('kg');
                $customer['total_cod'] = $parcel->price . ' ' . __('tk');

                $events = $parcel->events;

                foreach ($events as $event):
                    $event['title'] = __($event->title);
                    $event['date'] = date('d M Y', strtotime($event->created_at));
                    $event['time'] = date('g:i a', strtotime($event->created_at));
                    $event['processed_by'] = $event->user['first_name'] . ' ' . $event->user['last_name'];
                    $event['pickup_man'] = @$event->pickupPerson->user->first_name . ' ' . @$event->pickupPerson->user->last_name;
                    $event['pickup_man_phone'] = @$event->pickupPerson->phone_number ?? '';
                    $event['delivery_man'] = @$event->deliveryPerson->user->first_name . ' ' . @$event->deliveryPerson->user->last_name;
                    $event['delivery_man_phone'] = @$event->deliveryPerson->phone_number ?? '';

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
                endforeach;

                $data['merchant'] = $merchant;
                $data['customer'] = $customer;
                $data['events'] = $events;

                return $this->responseWithSuccess(__('successfully_delivered'), $data, 200);
            else:
                return $this->responseWithError(__('parcel_not_found'), [], 404);
            endif;

        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function parcelEvent($parcel_id, $title, $cancel_note = '', $user_id)
    {
        $parcel = $this->parcels->get($parcel_id);
        $parcel_event                      = new ParcelEvent();

        $parcel_event->parcel_id           = $parcel_id;
        $parcel_event->delivery_man_id     = $parcel->delivery_man_id;
        $parcel_event->pickup_man_id       = $parcel->pickup_man_id;
        $parcel_event->user_id             = $user_id;
        $parcel_event->title               = $title;
        $parcel_event->cancel_note         = $cancel_note;

        $parcel_event->save();

        $delivery_person = DeliveryMan::where('id',$parcel->delivery_man_id)->first();
        $pickup_person   = DeliveryMan::where('id',$parcel->pickup_man_id)->first();

        // merchant sms start
        $sms_template = SmsTemplate::where('subject',$title)->first();
        if(!blank($sms_template)):
            $sms_body = str_replace('{merchant_name}', $parcel->merchant->company, $sms_template->content);
            $sms_body = str_replace('{parcel_id}', $parcel->parcel_no, $sms_body);
            $sms_body = str_replace('{pickup_date_time}',  date('M d, Y', strtotime($parcel->pickup_date)), $sms_body);
            $sms_body = str_replace('{re_pickup_date_time}', date('M d, Y', strtotime($parcel->pickup_date)), $sms_body);
            $sms_body = str_replace('{delivery_date_time}', date('M d, Y', strtotime($parcel->delivery_date)), $sms_body);
            $sms_body = str_replace('{re_delivery_date_time}', date('M d, Y', strtotime($parcel->delivery_date)), $sms_body);
            $sms_body = str_replace('{current_date_time}', date('M d, Y h:i a'), $sms_body);
            $sms_body = str_replace('{return_date_time}', date('M d, Y h:i a'), $sms_body);
            $sms_body = str_replace('{our_company_name}', __('app_name'), $sms_body);
            $sms_body = str_replace('{pickup_man_name}', @$pickup_person->user->first_name, $sms_body);
            $sms_body = str_replace('{pickup_man_phone}', @$pickup_person->phone_number, $sms_body);
            $sms_body = str_replace('{delivery_man_name}', @$delivery_person->user->first_name, $sms_body);
            $sms_body = str_replace('{delivery_man_phone}', @$delivery_person->phone_number, $sms_body);
            $sms_body = str_replace('{cancel_note}', @$parcel->cancelnote->cancel_note, $sms_body);
            $sms_body = str_replace('{price}', @$parcel->price, $sms_body);
            $sms_body = str_replace('{otp_code}', $parcel->merchant_otp, $sms_body);
            $sms_body = str_replace('{invoice_no}', $parcel->customer_invoice_no, $sms_body);
            $sms_body = str_replace('{total_parcel}',  1, $sms_body);
            if($sms_template->sms_to_merchant):
                $this->smsSender($title, $parcel->merchant->phone_number, $sms_body, $sms_template->masking);
            endif;
        endif;
        //merchant sms end

        //customer sms start
        $customer_sms_template = CustomerParcelSmsTemplates::where('subject',$title)->first();
        if(!blank($customer_sms_template)):
            $sms_body = str_replace('{merchant_name}', $parcel->merchant->company, $customer_sms_template->content);
            $sms_body = str_replace('{parcel_id}', $parcel->parcel_no, $sms_body);
            $sms_body = str_replace('{pickup_date_time}',  date('M d, Y', strtotime($parcel->pickup_date)), $sms_body);
            $sms_body = str_replace('{re_pickup_date_time}', date('M d, Y', strtotime($parcel->pickup_date)), $sms_body);
            $sms_body = str_replace('{delivery_date_time}', date('M d, Y', strtotime($parcel->delivery_date)), $sms_body);
            $sms_body = str_replace('{re_delivery_date_time}', date('M d, Y', strtotime($parcel->delivery_date)), $sms_body);
            $sms_body = str_replace('{current_date_time}', date('M d, Y h:i a'), $sms_body);
            $sms_body = str_replace('{return_date_time}', date('M d, Y h:i a'), $sms_body);
            $sms_body = str_replace('{our_company_name}', __('app_name'), $sms_body);
            $sms_body = str_replace('{pickup_man_name}', @$pickup_person->user->first_name, $sms_body);
            $sms_body = str_replace('{pickup_man_phone}', @$pickup_person->phone_number, $sms_body);
            $sms_body = str_replace('{delivery_man_name}', @$delivery_person->user->first_name, $sms_body);
            $sms_body = str_replace('{delivery_man_phone}', @$delivery_person->phone_number, $sms_body);
            $sms_body = str_replace('{cancel_note}', @$parcel->cancelnote->cancel_note, $sms_body);
            $sms_body = str_replace('{price}', @$parcel->price, $sms_body);
            $sms_body = str_replace('{otp}', $parcel->otp, $sms_body);

            if($customer_sms_template->sms_to_customer):
                $this->smsSender($title, $parcel->customer_phone_number, $sms_body, $customer_sms_template->masking);
            endif;
        endif;
        //customer sms end

        return true;
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
                    $event['time']              = date('g:i a', strtotime($event->created_at));
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

                return $this->responseWithSuccess(__('successfully_delivered'), $data, 200);
            else:
                return $this->responseWithError(__('parcel_not_found'), [], 404);
            endif;

        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function myPickupMerchants(Request $request)
    {
        try{
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $page = $request->page ?? 1;

            $offset = ( $page * \Config::get('greenx.api_paginate') ) - \Config::get('greenx.api_paginate');
            $limit  = \Config::get('greenx.api_paginate');

            $my_pickup = Parcel::with('merchant.user')->groupBy('merchant_id')->where('pickup_man_id', $user->deliveryMan->id)->whereIn('status', ['pickup-assigned','re-schedule-pickup'])->orderByDesc('id')->latest()->skip($offset)->take($limit)->get(['merchant_id','pickup_shop_phone_number','pickup_address']);

            $data = $this->merchantReturnFormat($my_pickup);

            return $this->responseWithSuccess(__('successfully_found'),$data ,200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong'), [], 500);
        }
    }

    public function merchantReturnFormat($parcels)
    {
        foreach ($parcels as $parcel):
            $parcel['merchant_name'] = $parcel->merchant->user->first_name.' '.$parcel->merchant->user->last_name;
            $parcel['company'] = $parcel->merchant->company;
            $parcel['pickup_phone_number'] = $parcel->pickup_shop_phone_number;
            $address = $parcel->pickup_address;
            unset($parcel->pickup_address);
            $parcel['pickup_address'] = $address;
            unset($parcel->merchant_id);
            unset($parcel->pickup_shop_phone_number);
            unset($parcel->merchant);
        endforeach;

        return $parcels;
    }

    public function pickupReceived(Request $request)
    {
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(),[
                'id'    => 'required|max:50',
            ]);

            if ($validator->fails()){
                return $this->responseWithError('required_field_missing', $validator->errors(), 422);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()){
                return $this->responseWithError(__('unauthorized_user'), '', 404);
            }

            $stsResponse = $this->statusCheck($request->id, "received-by-pickup-man");
            if($stsResponse === true):
                $parcel          = Parcel::find($request->id);
                if ($parcel->status != 'pickup-assigned' && $parcel->status != 're-schedule-pickup'):
                    return $this->responseWithError(__('this_parcel_can_not_get_received'), [], 422);
                endif;

                $parcel->date    = date('Y-m-d');
                $parcel->status  = 'received-by-pickup-man';
                $parcel->save();

                $this->parcelEvent($parcel->id, 'parcel_received_by_pickup_man_event', $request->note, $user->id);

                DB::commit();

                return $this->responseWithSuccess(__('successfully_received'), [], 200);
            else:
                return $this->responseWithError(__($stsResponse), [], 500);
            endif;

        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__($stsResponse), [], 500);
        }
    }


    //Parcel Otp request
    public function parceldelivery(Request $request)
    {

        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(),[
                'id'    => 'required|max:50',
            ]);

            if ($validator->fails()){
                return $this->responseWithError('required_field_missing', $validator->errors(), 422);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()){
                return $this->responseWithError(__('unauthorized_user'), '', 404);
            }

            $parcel          = Parcel::find($request->id);

            if ($parcel->status == 'delivered-and-verified' || $parcel->status == 'delivered' || $parcel->status == 'partially-delivered'):
                return $this->responseWithError(__('this_parcel_has_already_been_delivered'), [], 422);
            endif;


            $otp_permission  = Setting::where('title', "delivery_otp")->first();

            if($otp_permission->value == trim('all')):

                $otp = rand(1000, 9999);
                $parcel->otp = $otp;
                $parcel->save();
                DB::commit();

                $this->parcelEvent($parcel->id, 'customer_parcel_delivery_otp_event', '', $user->id);
                return $this->responseWithOTPSuccess(__('successfully_otp_send_to_the_customer'), $otp, [], 200);

            elseif($otp_permission->value == trim('conditional')):

                if($parcel->price <=  $parcel->total_delivery_charge):
                    $otp = rand(1000, 9999);
                    $parcel->otp = $otp;
                    $parcel->save();
                    DB::commit();

                    $this->parcelEvent($parcel->id, 'customer_parcel_delivery_otp_event', '', $user->id);
                    return $this->responseWithOTPSuccess(__('successfully_otp_send_to_the_customer'), $otp, [], 200);

                else:
                    return $response =  $this->deleveryConfirm($parcel->id);
                endif;
            else:
                return $response =  $this->deleveryConfirm($parcel->id);
            endif;


        } catch (\Exception $e) {
           DB::rollback();
           return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function deleveryConfirm($id)
    {
        try{

            if (!$user = JWTAuth::parseToken()->authenticate()){
                return $this->responseWithError(__('unauthorized_user'), '', 404);
            }

            $stsResponse = $this->statusCheck($id, "delivered");
            if($stsResponse === true):
                $parcel                 = Parcel::find($id);
                $parcel->date           = date('Y-m-d');
                $parcel->status         = 'delivered';
                $this->accounts->incomeExpenseManage($id, 'delivered');
                $parcel->otp = rand(1000,9999);
                $this->parcelEvent($parcel->id, 'parcel_delivered_event', '', $user->id);
                $sms_template = CustomerParcelSmsTemplates::where('subject','delivery_confirm_otp')->first();
                $sms_body = str_replace('{merchant_name}', $parcel->merchant->company, $sms_template->content);
                $sms_body = str_replace('{parcel_id}', $parcel->parcel_no, $sms_body);
                $sms_body = str_replace('{otp}', $parcel->otp, $sms_body);
                $sms_body = str_replace('{our_company_name}', __('app_name'), $sms_body);
                $parcel->save();

                if($sms_template->sms_to_customer):
                    $this->smsSender('delivery_confirm_otp', $parcel->customer_phone_number, $sms_body, $sms_template->masking);
                endif;
                DB::commit();
                return $this->responseWithSuccess(__('successfully_delivered'), [], 200);
            else:
                return $this->responseWithError(__($stsResponse), [], 500);
            endif;

        }catch(\Exception $e){
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }

    }


    public function myReturn(Request $request)
    {
        try{
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }
            $page = $request->page ?? 1;

            $offset = ( $page * \Config::get('greenx.api_paginate') ) - \Config::get('greenx.api_paginate');
            $limit  = \Config::get('greenx.api_paginate');
            $return_man_id = $user->deliveryMan->id;

            $my_delivery    = Parcel::where('return_delivery_man_id', $return_man_id)
                ->where(function ($q){
                    $q->where('status','return-assigned-to-merchant');
                })
                ->whereNotIn('parcel_no', [DB::raw("Select parcel_no  from parcel_returns where return_man_id='$return_man_id' and status in ('return-assigned-to-merchant') ")])
                ->orderByDesc('id')
                ->latest()
                ->skip($offset)
                ->take($limit)
                ->get();

            $data = $this->parcelListReturnFormat($my_delivery);

            return $this->responseWithSuccess(__('successfully_found'),$data ,200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong'), [], 500);
        }

    }

    Public function ParcelReturn(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'id'    => 'required|max:50',
        ]);

        if ($validator->fails()){
            return $this->responseWithError('required_field_missing', $validator->errors(), 422);
        }

        $id = $request->id;
        $otpResponse =  $this->merchantReturnOtp($id);
        $note = $request->note;

        $stsResponse = $this->statusCheck($request->id, "returned-to-merchant");
        if($stsResponse === true):
            $parcel            = Parcel::find($id);
            if ($parcel->status == 'returned-to-merchant'):
                return $this->responseWithError(__('this_parcel_has_already_been_returned'), [], 422);
            endif;

            if(empty($otpResponse['phone'])):
            try{
                if (!$user = JWTAuth::parseToken()->authenticate()){
                    return $this->responseWithError(__('unauthorized_user'), '', 404);
                }
                $status = "returned-to-merchant";
                $parcel->date           = date('Y-m-d');
                $parcel->status         = $status;
                $parcel->save();
                $this->accounts->incomeExpenseManage($id, $status);
                $this->parcelEvent($parcel->id, 'parcel_returned_to_merchant_event', $note, $user->id);
                DB::commit();
                return $this->responseWithSuccess(__('successfully_returned'), [], 200);

            }catch(\Exception $e){
                DB::rollback();
                return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
            }
            else:
                return $this->responseWithSuccess(__('successfully_found'), $otpResponse , 200);
            endif;
        else:
            return $this->responseWithError(__($stsResponse), [], 500);
        endif;
    }

    Public function confirmReturned(Request $request)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()){
            return $this->responseWithError(__('unauthorized_user'), '', 404);
        }

        $validator = Validator::make($request->all(),[
                'id' => 'required|max:50',
                'otp' => 'required|max:10',
            ]);
        if ($validator->fails()){
            return $this->responseWithError('required_field_missing', $validator->errors(), 422);
        }
        $id = $request->id;
        $note = $request->note;
        $otp = $request->otp;
        $stsResponse = $this->statusCheck($request->id, "returned-to-merchant");
        if($stsResponse === true):
            $parcel                 = Parcel::find($id);
            if ($parcel->status == 'returned-to-merchant'):
                return $this->responseWithError(__('this_parcel_has_already_been_returned'), [], 422);
            endif;

            if ($parcel->merchant_otp == $otp):
                DB::beginTransaction();
                try{
                    $status = "returned-to-merchant";
                    $parcel->date           = date('Y-m-d');
                    $parcel->status         = $status;
                    $parcel->save();
                    $this->accounts->incomeExpenseManage($id, $status);
                    $this->parcelEvent($parcel->id, 'parcel_returned_to_merchant_event', $note, $user->id);
                    DB::commit();
                    return $this->responseWithSuccess(__('successfully_returned'), [], 200);

                }catch(\Exception $e){
                    DB::rollback();
                    return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
                }
            else:
                return $this->responseWithError(__('sorry_this_otp_not_matched_for_this_parcel'), [], 422);
            endif;
        else:
            return $this->responseWithError(__($stsResponse), [], 500);
        endif;

    }

    function merchantReturnOtp($parcel_id)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()){
            return $this->responseWithError(__('unauthorized_user'), '', 404);
        }

        $return_sms = SmsTemplate::where('subject','parcel_otp_event')->first();
        if($return_sms->sms_to_merchant):
            $otp = rand(1000, 9999);
            $parcel = Parcel::find($parcel_id);

               DB::beginTransaction();
               try{
                   $parcel->merchant_otp = $otp;
                   $parcel->save();
                   DB::commit();
                   $this->parcelEvent($parcel_id, 'parcel_otp_event', '', $user->id);
                   $data = [
                       'phone' => $parcel->merchant->phone_number,
                       'message' => "Successfully OTP Send",
                   ];
                   return $data;
               }catch(\Exception $e){
                   DB::rollback();
                   $data = [
                       'message' => "OTP Send Fail",
                       "error"  => $e->getMessage()
                   ];
                 return $data;
               }
        else:
            $data = [
                'phone' => '',
                'message' => "Return OTP not needed",

            ];
            return $data;

        endif;
    }



    function bulkReturnList(Request $request)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()){
            return $this->responseWithError(__('unauthorized_user'), '', 404);
        }
        $bulkReturn = BulkReturn::with('returnmerchant')
        ->where('delivery_man_id', $user->deliveryMan->id)->where('status', 'pending')->get();
        if(count($bulkReturn) > 0):
           return $this->responseWithSuccess(__('successfully_found'), $bulkReturn , 200);
        else:
            return $this->responseWithSuccess(__('parcel_not_found'), [] , 200);
        endif;
    }


    function bulkReturnDetails(Request $request)
    {

        if (!$user = JWTAuth::parseToken()->authenticate()){
            return $this->responseWithError(__('unauthorized_user'), '', 404);
        }

        $validator = Validator::make($request->all(),[
            'batch_no' => 'required'
        ]);
        if ($validator->fails()){
            return $this->responseWithError('required_field_missing', $validator->errors(), 422);
        }


        $parcels = ParcelReturn::with('returnmerchant', 'parcels')
        ->where([
            ['return_man_id', '=', $user->deliveryMan->id],
            ['batch_no', '=', $request->batch_no],
            ['status', '=', 'return-assigned-to-merchant']
        ])->get();
        if(count($parcels) > 0):
           return $this->responseWithSuccess(__('successfully_found'), $parcels , 200);
        else:
            return $this->responseWithSuccess(__('parcel_not_found'), [] , 200);
        endif;

    }

    public function bulkconfirmReturned(Request $request)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()){
            return $this->responseWithError(__('unauthorized_user'), '', 404);
        }

        $validator = Validator::make($request->all(), [
            'batch_no' => 'required'
        ]);
        if($validator->fails()){
            return $this->responseWithError('required_field_missing', $validator->errors(), 422);
        }

        if($this->merchantOtpsetting()):
             $result=  $this->bulkReturnOTPGenerate($request->batch_no, $user->deliveryMan->id);
            return $this->responseWithSuccess(__('successfully_found'), $result , 200);
        else:
            $return_success = $this->bulkRetrunToMerchant($request);
            if($return_success['success']== true):
                return $this->responseWithSuccess(__('successfully_returned'), [] , 200);
            else:
                return $this->responseWithError(__('something_went_wrong_please_try_again'), $return_success, 500);
            endif;

        endif;
    }

    function merchantOtpsetting()
    {
        $return_sms = SmsTemplate::where('subject','parcel_otp_event')->first();
        if($return_sms->sms_to_merchant):
            return true;
        else:
            return false;
        endif;
    }

    function bulkReturnOTPGenerate($batch_no, $reteurn_man_id)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()){
            return $this->responseWithError(__('unauthorized_user'), '', 404);
        }

        $parcels = ParcelReturn::select('parcel_no')
        ->where([
            ['return_man_id', '=', $reteurn_man_id],
            ['batch_no', '=', $batch_no],
            ['status', '=', 'return-assigned-to-merchant']
        ])->get();
        
        $otp = rand(1000, 9999);
         if(count($parcels) > 0):
            DB::beginTransaction();
            $batch= BulkReturn::where('batch_no', $batch_no)->first();
            $merchant_phone_number =   $batch->merchant->phone_number;
            try{
                $parcel_no=array();
                $invoice_no=array();
                foreach($parcels as $parcel)
                {
                   
                    $parcel_info = Parcel::where('parcel_no', $parcel->parcel_no)->where('status', 'return-assigned-to-merchant')->first();
                    $parcel_info->merchant_otp = $otp;
                    $parcel_info->save();
                    array_push($parcel_no, $parcel_info->parcel_no);
                    array_push($invoice_no, $parcel_info->customer_invoice_no);
                    
                    $parcel_event                           = new ParcelEvent();
                    $parcel_event->parcel_id                = $parcel_info->id;
                    $parcel_event->return_delivery_man_id   = $parcel_info->return_delivery_man_id;
                    $parcel_event->user_id                  = $user->id;
                    $parcel_event->title                    = 'parcel_otp_event';
                    $parcel_event->save();

                }
                 $parcel_no_list =  implode(",",  $parcel_no);
                 $invoice_no_list = implode(",",  $invoice_no);
                 $total_parcel = count($parcels);

                 $this->bulkparcelReturnEvent("parcel_otp_event", $otp,  $merchant_phone_number,  $parcel_no_list, $invoice_no_list, $total_parcel);
                 DB::commit();
                $data = [
                    'phone' => $merchant_phone_number,
                    'message' => "Successfully OTP Send",
                ];
                return $data;
            }catch(\Exception $e){
                DB::rollback();
                $data = [
                    'phone' => '',
                    'message' => "Sorry !! OTP generate fail try again",
                    'error' => $e->getMessage()
                ];
                return $data;
            }
        endif;
    }

    

    public function bulkRetrunToMerchant($request)
    {
        if (!$user = JWTAuth::parseToken()->authenticate()){
            return $this->responseWithError(__('unauthorized_user'), '', 404);
        }
        $batch_no = $request->batch_no;
        $parcels = ParcelReturn::select('parcel_no')
        ->where([
            ['batch_no', '=', $batch_no],
            ['status', '=', 'return-assigned-to-merchant']
        ])->get();
    
        if(count($parcels) > 0):
            DB::beginTransaction();
            $batch= BulkReturn::where('batch_no', $batch_no)->first();
            $merchant_phone_number =   $batch->merchant->phone_number;
            try{
                $parcel_no=array();
                $invoice_no=array();
                foreach($parcels as $parcel)
                {
                    //parcel_update 
                    $parcel_info = Parcel::where('parcel_no', $parcel->parcel_no)->where('status', 'return-assigned-to-merchant')->first();
                    $parcel_info->status = 'returned-to-merchant';
                    $parcel_info->date = date('Y-m-d');
                    $parcel_info->save();
                    $this->accounts->incomeExpenseManage($parcel_info->id, 'returned-to-merchant');
                    //return parcel update
                    $return_parcel = ParcelReturn::where([
                                    ['batch_no', '=', $batch_no],
                                    ['status', '=', 'return-assigned-to-merchant']
                                ])->first();
                    $return_parcel->status = 'returned-to-merchant';
                    $return_parcel->return_date = date('Y-m-d');
                    $return_parcel->save();
                    
                    array_push($parcel_no, $parcel_info->parcel_no);
                    array_push($invoice_no, $parcel_info->customer_invoice_no);
                    
                    $parcel_event                           = new ParcelEvent();
                    $parcel_event->parcel_id                = $parcel_info->id;
                    $parcel_event->return_delivery_man_id   = $parcel_info->return_delivery_man_id;
                    $parcel_event->user_id                  = $user->id;
                    $parcel_event->title                    = 'parcel_returned_to_merchant_event';
                    $parcel_event->save();

                }
                 $parcel_no_list =  implode(",",  $parcel_no);
                 $invoice_no_list = implode(",",  $invoice_no);
                 $total_parcel = count($parcels);
                 $this->bulkparcelReturnEvent("parcel_returned_to_merchant_event", '',  $merchant_phone_number,  $parcel_no_list, $invoice_no_list, $total_parcel);

                 $batch->status = "processed";
                 $batch->processed_by = $user->id;
                 $batch->save();
                 DB::commit();

                 $data = [
                    'success' => true,
                    'message' => "successfully_returned",
                ];
                return $data;
            }catch(\Exception $e){
                DB::rollback();
                $data = [
                    'success' => false,
                    'message' => "something_went_wrong_please_try_again",
                    'error' => $e->getMessage()
                ];
                return $data;
            }

        else:
            return $this->responseWithError(__('parcel_not_found'), [] , 422);
        endif;

        
    }


    function bulkOtpReturned(Request $request)
    {
        
       
        if (!$user = JWTAuth::parseToken()->authenticate()){
            return $this->responseWithError(__('unauthorized_user'), '', 404);
        }

        $validator = Validator::make($request->all(), [
            'batch_no' => 'required',
            'otp' => 'required'
        ]);
        if($validator->fails()){
            return $this->responseWithError('required_field_missing', $validator->errors(), 422);
        }

        $batch_no = $request->batch_no;
        $parcels = ParcelReturn::select('parcel_no')
        ->where([
            ['batch_no', '=', $batch_no],
            ['status', '=', 'return-assigned-to-merchant']
        ])->get();
        if(count($parcels) > 0): 
            if($this->bulkOtpCodeCheck($request)): 
                DB::beginTransaction();
                $batch= BulkReturn::where('batch_no', $batch_no)->first();
                $merchant_phone_number =   $batch->merchant->phone_number;
                try{
                    $parcel_no=array();
                    $invoice_no=array();
                    foreach($parcels as $parcel)
                    {
                        $parcel_info = Parcel::where('parcel_no', $parcel->parcel_no)->where('status', 'return-assigned-to-merchant')->first();
                        //parcel_update 
                        $parcel_info->status = 'returned-to-merchant';
                        $parcel_info->date = date('Y-m-d');
                        $parcel_info->save();
                        $this->accounts->incomeExpenseManage($parcel_info->id, 'returned-to-merchant');
                        //return parcel update
                        $return_parcel = ParcelReturn::where([
                                        ['batch_no', '=', $batch_no],
                                        ['status', '=', 'return-assigned-to-merchant']
                                    ])->first();
                        $return_parcel->status = 'returned-to-merchant';
                        $return_parcel->return_date = date('Y-m-d');
                        $return_parcel->save();
                        
                        array_push($parcel_no, $parcel_info->parcel_no);
                        array_push($invoice_no, $parcel_info->customer_invoice_no);
                        
                        $parcel_event                           = new ParcelEvent();
                        $parcel_event->parcel_id                = $parcel_info->id;
                        $parcel_event->return_delivery_man_id   = $parcel_info->return_delivery_man_id;
                        $parcel_event->user_id                  = $user->id;
                        $parcel_event->title                    = 'parcel_returned_to_merchant_event';
                        $parcel_event->save();
                        

                    }
                    $parcel_no_list =  implode(",",  $parcel_no);
                    $invoice_no_list = implode(",",  $invoice_no);
                    $total_parcel = count($parcels);
                    $this->bulkparcelReturnEvent("parcel_returned_to_merchant_event", '',  $merchant_phone_number,  $parcel_no_list, $invoice_no_list, $total_parcel);

                    $batch->status = "processed";
                    $batch->processed_by = $user->id;
                    $batch->save();

                    DB::commit();
                    return $this->responseWithSuccess(__('successfully_returned'), [] , 200);
                }catch(\Exception $e){
                    DB::rollback();
                    return $this->responseWithError(__('something_went_wrong'), [], 500);
                }
            else:
                return $this->responseWithError(__('sorry_this_otp_not_matched'), [], 422);

            endif;
        else:
            return $this->responseWithError(__('parcel_not_found'), [] , 422);
        endif;

    }

    public function bulkOtpCodeCheck($request)
    {
        $otp_code = $request->otp;
        $batch_no = $request->batch_no;
        $parcels = DB::table("parcel_returns as pr")
                    ->join('parcels as p', 'p.parcel_no', '=', 'pr.parcel_no')
                    ->where('pr.batch_no', $batch_no)
                    ->where('p.merchant_otp', $otp_code)
                    ->count();

        if($parcels > 0)
        {
            return true;
        }else{
            return false;
        }
        
    }

    public function bulkparcelReturnEvent($title, $otp, $phone_number , $parcel_no_list, $invoice_no_list, $total_parcel)
    {
         // merchant sms start
         $sms_template = SmsTemplate::where('subject',$title)->first();
         if (!blank($sms_template)):
             if($sms_template->sms_to_merchant):
                 $sms_body = str_replace('{parcel_id}', $parcel_no_list, $sms_template->content);
                 $sms_body = str_replace('{otp_code}', $otp, $sms_body);
                 $sms_body = str_replace('{invoice_no}',  $invoice_no_list, $sms_body);
                 $sms_body = str_replace('{total_parcel}',  $total_parcel, $sms_body);
                 //send sms
                 $this->smsSender($title, $phone_number, $sms_body, $sms_template->masking);

             endif;
             //merchant sms end
         endif;
    }

    public function statusCheck($parcel_id, $status)
    {
        $parcel = Parcel::find($parcel_id);
        if($parcel->status =='delivered' || $parcel->status =='partially-delivered' || $parcel->status == 'delivered-and-verified'):
            $data = [
                'success'=>false,
                'message'=> "sorry_this_parcel_already_assign_or_delivered",
            ];
            return $data['message'];
        elseif($parcel->status == 'pending' && ($status == 'pickup-assigned' || $status == 'deleted') ):
            return true;
        elseif($parcel->status == 'pickup-assigned' && ($status == 're-schedule-pickup' || $status == 'received-by-pickup-man' || $status == 'received') ):
            return true;
        elseif($parcel->status == 're-schedule-pickup' && ($status == 're-schedule-pickup' || $status == 'received-by-pickup-man' || $status == 'received' || $status == 'deleted' ) ):
            return true;
        elseif($parcel->status == 'received-by-pickup-man' && $status == 'received' ):
                return true;
        elseif($parcel->status == 'received' && ($status == 'transferred-to-hub' || $status == 'delivery-assigned' || $status == 'cancel') ):
            return true;
        elseif($parcel->status == 'transferred-to-hub' && ($status == 'transferred-received-by-hub'  || $status == 'cancel') ):
            return true;
        elseif($parcel->status == 'transferred-received-by-hub' && ($status == 'transferred-to-hub'  || $status == 'delivery-assigned' || $status == 'cancel') ):
            return true;
        elseif($parcel->status == 'delivery-assigned' && ($status == 're-schedule-delivery'  || $status == 'returned-to-greenx' || $status == 'partially-delivered' || $status == 'delivered' || $status == 'cancel' ) ):
            return true;
        elseif($parcel->status == 're-schedule-delivery' && ($status == 'transferred-to-hub'  || $status == 'delivery-assigned' || $status == 'cancel' ) ):
            return true;
        elseif($parcel->status == 'returned-to-greenx' && $status == 'return-assigned-to-merchant' ):
            return true;
        elseif($parcel->status == 'return-assigned-to-merchant' && $status == 'returned-to-merchant' ):
            return true;
        else:
            $data = [
                'success'=>false,
                'message'=> "Sorry this parcel  already ".($parcel->status == "re-schedule-delivery" ?  "Hold" : $parcel->status),
            ];
            return $data['message'];
        endif;

    }


}
