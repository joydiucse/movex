<?php

namespace App\Http\Controllers\Api\V10;

use JWTAuth;
use App\Models\Parcel;
use App\Models\DeliveryMan;
use App\Models\ParcelEvent;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use App\Traits\SmsSenderTrait;
use App\Traits\MerchantApiTrait;
use App\Traits\ShortenLinkTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\CustomerParcelSmsTemplates;
use App\Repositories\Interfaces\ParcelInterface;
use App\Repositories\Interfaces\AccountInterface;
use App\Repositories\Interfaces\Merchant\MerchantInterface;

class ParcelController extends Controller
{
    use ApiReturnFormatTrait;
    use SmsSenderTrait;
    use MerchantApiTrait, ShortenLinkTrait;


    protected $parcels;
    protected $accounts;
    protected $merchants;

    public function __construct(ParcelInterface $parcels, AccountInterface $accounts,MerchantInterface $merchants)
    {
        $this->parcels          = $parcels;
        $this->accounts         = $accounts;
        $this->merchants        = $merchants;
    }

    public function myPickup(Request $request)
    {
        try{
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $page = $request->page ?? 1;

            $offset = ( $page * settingHelper('paginate_api_list') ) - settingHelper('paginate_api_list');
            $limit  = settingHelper('paginate_api_list');

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

            $offset = ( $page * settingHelper('paginate_api_list') ) - settingHelper('paginate_api_list');
            $limit  = settingHelper('paginate_api_list');

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

            $offset = ( $page * settingHelper('paginate_api_list') ) - settingHelper('paginate_api_list');
            $limit  = settingHelper('paginate_api_list');

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

            $offset = ( $page * settingHelper('paginate_api_list') ) - settingHelper('paginate_api_list');
            $limit  = settingHelper('paginate_api_list');

            $my_delivery    = Parcel::with('merchant','pickupMan.user','deliveryMan.user')->where('delivery_man_id', $user->deliveryMan->id)->whereIn('status', ['delivery-assigned','re-schedule-delivery'])->orderByDesc('id')->latest()->skip($offset)->take($limit)->get();

            $data = $this->parcelListReturnFormat($my_delivery);

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

            $offset = ( $page * settingHelper('paginate_api_list') ) - settingHelper('paginate_api_list');
            $limit  = settingHelper('paginate_api_list');

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

            $offset = ( $page * settingHelper('paginate_api_list') ) - settingHelper('paginate_api_list');
            $limit  = settingHelper('paginate_api_list');

            $completed    = Parcel::with('merchant','pickupMan.user','deliveryMan.user')->where('delivery_man_id', $user->deliveryMan->id)->whereIn('status', ['delivered','delivered-and-verified'])->orderByDesc('id')->latest()->skip($offset)->take($limit)->get();

            $data = $this->parcelListReturnFormat($completed);

            return $this->responseWithSuccess(__('successfully_found'),$data ,200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong'), [], 500);
        }

    }

    public function parcelListReturnFormat($parcels)
    {
        foreach ($parcels as $parcel):
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

            $parcel                       = Parcel::find($request->id);
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
            ]);

            if ($validator->fails()){
                return $this->responseWithError('required_field_missing', $validator->errors(), 422);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()){
                return $this->responseWithError(__('unauthorized_user'), '', 404);
            }

            $parcel                 = Parcel::find($request->id);

            if ($parcel->status == 'delivered' || $parcel->status == 'delivered-and-verified' || $parcel->status == 'partially-delivered'):
                return $this->responseWithError(__('this_parcel_has_already_confirmed_as_delivered'), [], 422);
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
        if($sms_template->sms_to_merchant):
            $this->smsSender($title, $parcel->merchant->phone_number, $sms_body, $sms_template->masking);
        endif;
        //merchant sms end

        //customer sms start
        $customer_sms_template = CustomerParcelSmsTemplates::where('subject',$title)->first();
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

        if($customer_sms_template->sms_to_customer):
            $this->smsSender($title, $parcel->customer_phone_number, $sms_body, $customer_sms_template->masking);
        endif;
        //customer sms end

        return true;
    }

    public function parcelDetailsV10($id)
    {
        try{
            $parcel = $this->parcels->get($id);

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

                return $this->responseWithSuccess(__('successfully_found'), $data, 200);
            else:
                return $this->responseWithError(__('parcel_not_found'), [], 404);
            endif;

        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }


    public function getTrack(Request $request,$parcel_no)
    {
        try {
            $data = $this->getMerchant($request);
            if ($data['code'] == 401):
                return response()->json($data['response'], $data['code']);
            else:
                $merchant = $data['merchant'];
            endif;

            $parcel = Parcel::where('parcel_no',$parcel_no)->where('merchant_id', $merchant->id)->first();

            if (!blank($parcel)):
                $response = [
                    'status' => 200,
                    'delivery_status' => $parcel->status,
                ];
                $code = 200;
            else:
                $response = [
                    'status' => 404,
                    'message' => __('parcel_not_found_or_access_denied'),
                ];
                $code = 404;
            endif;

            return response()->json($response, $code);
        } catch (\Exception $e){
            $response = [
                'status'    => 500,
                'message'   => __('something_went_wrong_please_try_again'),
            ];
            return response()->json($response, 500);
        }
    }



    public function create(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $validator = Validator::make($request->all(),[
                'delivery_area'     => 'required|in:same_day,next_day,sub_city,outside_dhaka',
                'cod'               => 'required|numeric',
                'selling_price'     => 'required|numeric',
                'customer_name'     => 'required',
                'customer_invoice_no'   => 'required',
                'customer_phone_number' => 'required|numeric',
                'customer_address'  => 'required',
                'fragile'           => 'bool',
                'weight'            => 'numeric|max:10',
            ]);

            $code = 200;

            if ($validator->fails()) :
                $response = [
                  'status' => 422,
                  'message'=>   $validator->errors()
                ];

                return response()->json($response, 422);
            endif;

            $data = $this->getMerchant($request);
            if ($data['code'] == 401):
                return response()->json($data['response'], $data['code']);
            else:
                $merchant = $data['merchant'];
            endif;

            $fragile_charge = number_format(0, 2);
            $fragile        = 0;

            $packaging_charge = number_format(0, 2);
            $packaging        = 'no';

            if($request->fragile){
                $fragile        = 1;
                $fragile_charge = settingHelper('fragile_charge');
            }

            // cod charge define by location
            if($request->delivery_area == "same_day" || $request->delivery_area == "next_day" || $request->delivery_area == "frozen"):
                $location            = 'dhaka';
            elseif($request->delivery_area == "sub_city"):
                $location            = 'sub_city';
            elseif($request->delivery_area == "outside_dhaka"):
                $location            = 'outside_dhaka';
            elseif($request->delivery_area == "third_party_booking"):
                $location            = 'third_party_booking';
            endif;

            // Start Charge calculate
            $merchant              = $this->merchants->get($merchant->id);
            $charge                = data_get($merchant->charges, '1'.'.'.$request->delivery_area);
            $cod_charge            = data_get($merchant->cod_charges, $location);

            $vat                   = $merchant->vat ?? 0.00;

            $total_delivery_charge = $charge + $packaging_charge + $fragile_charge + ($request->cod / 100 * $cod_charge);

            $total_delivery_charge += $total_delivery_charge / 100 * $vat;
            $payable               = $request->cod - $total_delivery_charge;

            $parcel                      = new Parcel();
            $parcel->parcel_no           = 'MVX'.rand(10000000000,99999999999);
            $parcel->short_url           = $this->get_link($parcel->parcel_no);
            $parcel->merchant_id         = $merchant->id;
            $parcel->price               = $request->cod;
            $parcel->selling_price       = $request->selling_price;
            $parcel->customer_name       = $request->customer_name;
            $parcel->customer_invoice_no = $request->customer_invoice_no;
            $parcel->customer_phone_number  = $request->customer_phone_number;
            $parcel->customer_address    = $request->customer_address;
            $parcel->note                = $request->note;

            // Charge
            $parcel->packaging                = $packaging;
            $parcel->packaging_charge         = $packaging_charge;
            $parcel->fragile                  = $fragile;
            $parcel->fragile_charge           = $fragile_charge;

            $parcel->weight                = 1;
            $parcel->parcel_type           = $request->delivery_area;
            $parcel->charge                = $charge;
            $parcel->cod_charge            = $cod_charge;
            $parcel->vat                   = $vat;
            $parcel->total_delivery_charge = floor($total_delivery_charge);
            $parcel->payable               = ceil($payable);
            $parcel->location              = $location;
            // End charge

            // pickup shop details
            $parcel->pickup_shop_phone_number    = $request->pickup_shop_phone_number ?? $merchant->shops->where('default', true)->first()->shop_phone_number;
            $parcel->pickup_address              = $request->pickup_address  ?? $merchant->shops->where('default', true)->first()->address;
            $parcel->pickup_hub_id               = $request->pickup_hub != '' ? $request->pickup_hub : $merchant->user->hub_id;

            $parcel->user_id = $merchant->user->id;
            // pickup and delivery time
            if($request->delivery_area == 'frozen'){

                $pickup_date   = date('Y-m-d');
                $pickup_time   = date('h:i:s');
                $delivery_date = date("Y-m-d", strtotime('+2 hours', strtotime($pickup_date)));
                $delivery_time = date("h:i:s", strtotime('+2 hours', strtotime($pickup_time)));

            }elseif($request->delivery_area == 'same_day'){

                if(date('H') >= settingHelper('pickup_accept_start') && date('H') <= settingHelper('pickup_accept_end')){
                    $pickup_date   = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
                    $delivery_date = date("Y-m-d", strtotime('+1 days', strtotime(date('Y-m-d'))));
                }else{
                    $pickup_date   = date('Y-m-d');
                    $delivery_date = date("Y-m-d");
                }

            }elseif($request->delivery_area == 'outside_dhaka'){

                if(date('H') >= settingHelper('pickup_accept_start') && date('H') <= settingHelper('pickup_accept_end')){

                    $days = settingHelper('outside_dhaka_days') + 1;

                    $pickup_date   = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
                    $delivery_date = date("Y-m-d", strtotime('+'.$days.' days', strtotime(date('Y-m-d'))));
                }else{

                    $days = settingHelper('outside_dhaka_days');

                    $pickup_date   = date('Y-m-d');
                    $delivery_date = date("Y-m-d", strtotime('+'.$days.' days', strtotime(date('Y-m-d'))));
                }

            }else{

                if(date('H') > settingHelper('pickup_accept_start') && date('H') <= settingHelper('pickup_accept_end')){
                    $pickup_date   = date('Y-m-d');
                    $delivery_date = date("Y-m-d", strtotime('+2 days', strtotime(date('Y-m-d'))));
                }else{
                    $pickup_date   = date('Y-m-d');
                    $delivery_date = date("Y-m-d", strtotime('+1 days', strtotime(date('Y-m-d'))));
                }
            }

            $parcel->pickup_date   = $pickup_date;

            $parcel->date   = date('Y-m-d');

            if(isset($pickup_time)):
                $parcel->pickup_time   = $pickup_time ?? '';
            endif;
            $parcel->delivery_date = $delivery_date;
            if(isset($delivery_time)):
                $parcel->delivery_time   = $delivery_time ?? '';
            endif;
            $parcel->save();

            $parcel_event                      = new ParcelEvent();
            $parcel_event->parcel_id           = $parcel->id;
            $parcel_event->user_id             = $merchant->user->id;
            $parcel_event->title               = 'parcel_create_event';
            $parcel_event->hub_id              = $parcel->hub_id;
            $parcel_event->save();

            DB::commit();

            $response = [
                'status'    => 201,
                'message'   => __('parcel_created_successfully'),
                'parcel'    => $parcel->makeHidden(['hub_id','user_id','packaging','fragile','merchant_id','id','third_party_id','pickup_hub_id','date']),
            ];

            return response()->json($response, 201);

        } catch (\Exception $e){
           DB::rollback();
            $response = [
                'status'    => 500,
                'message'   => __('something_went_wrong_please_try_again'),
            ];
            return response()->json($response, 500);
        }
    }

    public function parcelList(Request $request)
    {
        try{
            $data = $this->getMerchant($request);
            if ($data['code'] == 401):
                return response()->json($data['response'], $data['code']);
            else:
                $merchant = $data['merchant'];
            endif;

            $page = $request->page ?? 1;

            $per_page = $request->per_page ?? \Config::get('greenx.api_paginate');

            $offset = ( $page * $per_page ) - $per_page;
            $limit  = $per_page;

            $response = [
              'status' => 200,
              'message' => 'parcel_list',
              'parcels' => array_values($merchant->parcels->skip($offset)->take($limit)
                  ->makeHidden(['delivery_man_id','pickup_man_id','return_delivery_man_id','transfer_delivery_man_id',
                      'transfer_to_hub_id','hub_id','user_id','packaging','fragile','merchant_id','id','pickup_hub_id',
                      'date','withdraw_id','delivery_fee','pickup_fee','return_fee','pickup_time','delivery_time','status_before_cancel',
                      'is_partially_delivered','price_before_delivery','is_paid','third_party_id','otp'])
                  ->toArray()),
            ];

            return response()->json($response, 200);
        } catch (\Exception $e){
            $response = [
                'status'    => 500,
                'message'   => __('something_went_wrong_please_try_again'),
            ];
            return response()->json($response, 500);
        }
    }


}
