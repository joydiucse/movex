<?php

namespace App\Repositories;
use App\Models\CustomerParcelSmsTemplates;
use App\Models\DeliveryMan;
use App\Models\DistrictZila;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelEvent;
use App\Models\SmsTemplate;
use App\Models\ThirdParty;
use App\Models\User;
use App\Repositories\Interfaces\ParcelInterface;
use App\Repositories\Interfaces\AccountInterface;
use App\Repositories\Interfaces\Merchant\MerchantInterface;
use App\Traits\PaperFlyParcel;
use App\Traits\ShortenLinkTrait;
use App\Traits\SmsSenderTrait;
use Sentinel;
use DB;
use Image;
use function Symfony\Component\String\b;
use App\Models\ParcelReturn;
use App\Models\BulkReturn;

class ParcelRepository implements ParcelInterface {

    use SmsSenderTrait, ShortenLinkTrait, PaperFlyParcel;

    protected $merchants;
    protected $accounts;

    public function __construct(MerchantInterface $merchants,AccountInterface $accounts)
    {
        $this->merchants    = $merchants;
        $this->accounts     = $accounts;

    }

    public function all()
    {
        return Parcel::all();
    }

    public function paginate($limit)
    {
        return Parcel::orderBy('id', 'desc')
            ->when(!hasPermission('read_all_parcel'), function ($query){
                $query->where('hub_id', \Sentinel::getUser()->hub_id)
                      ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                      ->orWhereNull('pickup_hub_id')
                      ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
            })
            ->paginate($limit);
    }

    public function get($id)
    {
        return Parcel::find($id);
    }

    public function getMerchants()
    {
        return Merchant::all();
    }

    public function getDeliveryMan()
    {
        return DeliveryMan::all();
    }

    public function chargeDetails($request)
    {
        $packaging_charge = number_format(0, 2);
        if($request->packaging != 'no'){
            $packaging_charge = settingHelper('package_and_charges')->where('id',$request->packaging)->first()->charge;
        }

        $fragile_charge = number_format(0, 2);
        if($request->fragile == 1){
            $fragile_charge = settingHelper('fragile_charge');
        }

        $cod = number_format(0, 2);
        if($request->cod != ""){
            $cod = $request->cod;
        }

        if($request->merchant == "" || $request->weight == "" || $request->parcel_type == ""){

            $data['charge']                 = number_format(0, 2);
            $data['cod_charge']             = number_format(0, 2);
            $data['vat']                    = number_format(0, 2);
            $data['cod']                    = number_format($cod, 2);

            $data['total_delivery_charge'] = $data['charge'] + $data['cod_charge'] + $packaging_charge + $fragile_charge;
            $data['vat']                   = number_format($data['total_delivery_charge'] / 100 * $data['vat'], 2);
            $data['total_delivery_charge'] += $data['vat'];

            $data['payable']                = number_format($cod - $data['total_delivery_charge'], 2);

        }else{

            if($request->parcel_type == "same_day" || $request->parcel_type == "next_day" || $request->parcel_type == "frozen"):
                $location            = 'dhaka';
            elseif($request->parcel_type == "sub_city"):
                $location            = 'sub_city';
            elseif($request->parcel_type == "outside_dhaka"):
                $location            = 'outside_dhaka';
            elseif($request->parcel_type == "third_party_booking"):
                $location            = 'third_party_booking';
            endif;


            $merchant                      = $this->merchants->get($request->merchant);
            $data['charge']                = data_get($merchant->charges, $request->weight.'.'.$request->parcel_type);

            $data['cod_charge']            = data_get($merchant->cod_charges, $location);
            $data['vat']                   = $merchant->vat ?? 0.00;

            $data['cod_charge']            = floor($cod / 100 * $data['cod_charge']);
            $data['total_delivery_charge'] = $data['charge'] + $data['cod_charge'] + $packaging_charge + $fragile_charge;
            $data['vat']                   = floor($data['total_delivery_charge'] / 100 * $data['vat']);


            $data['total_delivery_charge'] += $data['vat'];
            $data['charge']                 = number_format($data['charge'], 2);
            $data['cod_charge']             = number_format($data['cod_charge'], 2);
            $data['vat']                    = number_format($data['vat'], 2);
            $data['cod']                    = number_format($cod, 2);


            $data['payable']                = number_format(ceil($cod - $data['total_delivery_charge']), 2);

        }


        $data['packaging_charge']       = number_format($packaging_charge, 2);
        $data['fragile_charge']         = number_format($fragile_charge, 2);

        return $data;

    }

    public function store($request)
    {
         DB::beginTransaction();
         try{

            $fragile_charge = number_format(0, 2);
            $fragile        = 0;

            $packaging_charge = number_format(0, 2);
            $packaging        = 'no';

            if(isset($request->fragile)){
                $fragile        = 1;
                $fragile_charge = settingHelper('fragile_charge');

                if($request->packaging != 'no'){
                    $packaging        = $request->packaging;
                    $packaging_charge = settingHelper('package_and_charges')->where('id',$request->packaging)->first()->charge;
                }
            }

            // cod charge define by location
            if($request->parcel_type == "same_day" || $request->parcel_type == "next_day" || $request->parcel_type == "frozen"):
                $location            = 'dhaka';
            elseif($request->parcel_type == "sub_city"):
                $location            = 'sub_city';
            elseif($request->parcel_type == "outside_dhaka"):
                $location            = 'outside_dhaka';
            elseif($request->parcel_type == "third_party_booking"):
                $location            = 'third_party_booking';
            endif;

            // Start Charge calculate
            $merchant              = $this->merchants->get($request->merchant);
            $charge                = data_get($merchant->charges, $request->weight.'.'.$request->parcel_type);
            $cod_charge            = data_get($merchant->cod_charges, $location);

            $vat                   = $merchant->vat ?? 0.00;

            $total_delivery_charge = $charge + $packaging_charge + $fragile_charge + ($request->price / 100 * $cod_charge);
            $total_delivery_charge += $total_delivery_charge / 100 * $vat;
            $payable               = $request->price - $total_delivery_charge;
            // End charge calculate

            // echo floor($total_delivery_charge);
            // echo '<br>';
            // echo ceil($payable);

            // exit();


            // if( number_format(number_format($total_delivery_charge, 2, '.', '') + number_format($payable, 2, '.', ''), 2, '.', '')  != number_format($request->price, 2, '.', '')){
            //     $total_delivery_charge = $total_delivery_charge - 0.01;
            // }

            $parcel                      = new Parcel();
            $parcel->parcel_no           = 'MVX'.rand(10000000000,99999999999);
            $parcel->short_url           = $this->get_link($parcel->parcel_no);

            $parcel->merchant_id         = $request->merchant;
            $parcel->price               = $request->price;
            //$parcel->selling_price       = $request->selling_price;
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

            $parcel->weight                = $request->weight;
            $parcel->parcel_type           = $request->parcel_type;
            $parcel->charge                = $charge;
            $parcel->cod_charge            = $cod_charge;
            $parcel->vat                   = $vat;
            $parcel->total_delivery_charge = floor($total_delivery_charge);
            $parcel->payable               = ceil($payable);
            $parcel->location              = $location;
            // End charge

            // Pathao Ares
             $parcel->pathao_city              = $request->city;
             $parcel->pathao_zone              = $request->zone;
             $parcel->pathao_area              = $request->area;

             ///// product_details
             $parcel->product_details              = $request->product_details;


            // pickup shop details
            //$parcel->pickup_shop_phone_number    = $request->shop_phone_number;
            //$parcel->pickup_address              = $request->shop_address;
            $parcel->pickup_hub_id               = $request->pickup_hub != '' ? $request->pickup_hub : $merchant->user->hub_id;

            $parcel->shop_id                     = $request->shop != '' ? $request->shop : ($merchant->shops->where('default', true)->first() ? $merchant->shops->where('default', true)->first()->id : null);

            $parcel->user_id = $request->created_by != "" ? $request->created_by : Sentinel::getUser()->id;
            // pickup and delivery time
            if($request->parcel_type == 'frozen'){

                $pickup_date   = date('Y-m-d');
                $pickup_time   = date('h:i:s');
                $delivery_date = date("Y-m-d", strtotime('+2 hours', strtotime($pickup_date)));
                $delivery_time = date("h:i:s", strtotime('+2 hours', strtotime($pickup_time)));

            }elseif($request->parcel_type == 'same_day'){

               if(date('H') >= settingHelper('pickup_accept_start') && date('H') <= settingHelper('pickup_accept_end')){
                    $pickup_date   = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
                    $delivery_date = date("Y-m-d", strtotime('+1 days', strtotime(date('Y-m-d'))));
               }else{
                    $pickup_date   = date('Y-m-d');
                    $delivery_date = date("Y-m-d");
               }

            }elseif($request->parcel_type == 'outside_dhaka'){

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

            $this->parcelEvent($parcel->id, 'parcel_create_event');

            DB::commit();
            return true;

         } catch (\Exception $e) {
             DB::rollback();
             // dd($e);
             return false;
         }
    }

    public function update($request)
    {
        DB::beginTransaction();
        try{

            $fragile_charge = number_format(0, 2);
            $fragile        = 0;

            $packaging_charge = number_format(0, 2);
            $packaging        = 'no';

            if(isset($request->fragile)){
                $fragile        = 1;
                $fragile_charge = settingHelper('fragile_charge');

                if($request->packaging != 'no'){
                    $packaging        = $request->packaging;
                    $packaging_charge = settingHelper('package_and_charges')->where('id',$request->packaging)->first()->charge;
                }

            }

            // cod charge define by location
            if($request->parcel_type == "same_day" || $request->parcel_type == "next_day" || $request->parcel_type == "frozen"):
                $location            = 'dhaka';
            elseif($request->parcel_type == "sub_city"):
                $location            = 'sub_city';
            elseif($request->parcel_type == "outside_dhaka"):
                $location            = 'outside_dhaka';
            elseif($request->parcel_type == "third_party_booking"):
                $location            = 'third_party_booking';
            endif;

            // Start Charge calculate
            $merchant              = $this->merchants->get($request->merchant);
            $charge                = data_get($merchant->charges, $request->weight.'.'.$request->parcel_type);
            $cod_charge            = data_get($merchant->cod_charges, $location);
            $vat                   = $merchant->vat ?? 0.00;
            $total_delivery_charge = $charge + $packaging_charge + $fragile_charge + ($request->price / 100 * $cod_charge);
            $total_delivery_charge += $total_delivery_charge / 100 * $vat;

            $payable               = $request->price - $total_delivery_charge;

            // if( number_format(number_format($total_delivery_charge, 2, '.', '') + number_format($payable, 2, '.', ''), 2, '.', '')  != number_format($request->price, 2, '.', '')){
            //     $total_delivery_charge = $total_delivery_charge - 0.01;
            // }
            // End charge calculate
            $additional_info['parcel_old_data'] = Parcel::find($request->id);
            $parcel                      = Parcel::find($request->id);
            $parcel->merchant_id         = $request->merchant;
            if(!hasPermission('parcel_price_update')){
                $parcel->price               = $parcel->price;
            }else{
                $parcel->price               = $request->price;
            }

            //$parcel->selling_price       = $request->selling_price;
            $parcel->customer_name       = $request->customer_name;
            $parcel->customer_invoice_no = $request->customer_invoice_no;

            if(!hasPermission('parcel_customer_phone_update')){
                $parcel->customer_phone_number  = $parcel->customer_phone_number;
            }else{
                $parcel->customer_phone_number  = $request->customer_phone_number;
            }
            $parcel->customer_phone_number  = $request->customer_phone_number;
            $parcel->customer_address    = $request->customer_address;
            $parcel->note                = $request->note;

            // Charge
            $parcel->packaging                = $packaging;
            $parcel->packaging_charge         = $packaging_charge;
            $parcel->fragile                  = $fragile;
            $parcel->fragile_charge           = $fragile_charge;

            $parcel->weight                = $request->weight;
            $parcel->charge                = $charge;
            //$parcel->cod_charge            = $cod_charge;
            $parcel->vat                   = $vat;
            $parcel->total_delivery_charge = floor($total_delivery_charge);
            $parcel->payable               = ceil($payable);
            $parcel->location              = $location;
            // End charge

            // Pathao Ares
            $parcel->pathao_city              = $request->city;
            $parcel->pathao_zone              = $request->zone;
            $parcel->pathao_area              = $request->area;


            ///// product_details
            $parcel->product_details              = $request->product_details;

            // pickup shop details
            //$parcel->pickup_shop_phone_number    = $request->shop_phone_number;
            //$parcel->pickup_address              = $request->shop_address;
            $parcel->pickup_hub_id               = $request->pickup_hub != '' ? $request->pickup_hub : ($parcel->pickup_hub_id != '' ? $parcel->pickup_hub_id : $merchant->user->hub_id);

            $parcel->shop_id                     = $request->shop != '' ? $request->shop : ($merchant->shops->where('default', true)->first() ? $merchant->shops->where('default', true)->first()->id : null);

            if($parcel->parcel_type != $request->parcel_type):

                if($request->parcel_type == 'frozen'){
                    $pickup_date   = date('Y-m-d');
                    $pickup_time   = date('h:i:s');
                    $delivery_date = date("Y-m-d", strtotime('+2 hours', strtotime($pickup_date)));
                    $delivery_time = date("h:i:s", strtotime('+2 hours', strtotime($pickup_time)));
                }elseif($request->parcel_type == 'same_day'){
                    if(date('H') > settingHelper('pickup_accept_start') && date('H') <= settingHelper('pickup_accept_end')){
                        $pickup_date   = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
                        $delivery_date = date("Y-m-d", strtotime('+1 days', strtotime(date('Y-m-d'))));
                    }else{
                        $pickup_date   = date('Y-m-d');
                        $delivery_date = date("Y-m-d");
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
                if(isset($pickup_time)):
                    $parcel->pickup_time      = $pickup_time ?? '';
                endif;
                $parcel->delivery_date = $delivery_date;
                if(isset($delivery_time)):
                    $parcel->delivery_time   = $delivery_time ?? '';
                endif;
            endif;

            $parcel->user_id    = $request->created_by != "" ? $request->created_by : Sentinel::getUser()->id;

            $parcel->parcel_type           = $request->parcel_type;
            $parcel->save();

            $additional_info['parcel_new_data'] = $parcel;
            //dd($additional_info);

            $this->parcelEvent($parcel->id, 'parcel_update_event','','','','','',null,null,'',$additional_info);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function parcelDelete($request)
    {
        DB::beginTransaction();
        try{

            // $this->accounts->incomeExpenseManageCancel($request->id, 'cancel');

            $parcel                         = Parcel::find($request->id);
            $parcel->status_before_cancel   = $parcel->status;
            $parcel->status                 = 'deleted';
            $parcel->save();

            $this->parcelEvent($parcel->id, 'parcel_delete_event', '', '', '', $request->cancel_note);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function imageUpload($image, $type, $delivery_man_id)
    {
        $delivery = DeliveryMan::find($delivery_man_id);
        if($delivery->driving_license != "" && file_exists($delivery->driving_license)):
            unlink($delivery->driving_license);
        endif;

        $requestImage           = $image;
        $fileType               = $requestImage->getClientOriginalExtension();
        $originalImage          = date('YmdHis') .'-'. $type . rand(1, 50) . '.' . $fileType;
        $directory              = 'admin/'.$type.'/';

        if(!is_dir($directory)) {
            mkdir($directory);
        }
        $originalImageUrl       = $directory . $originalImage;
        Image::make($requestImage)->save($originalImageUrl, 80);
        return $originalImageUrl;
    }

    public function statusChange($request)
    {
        $user = User::find($request['id']);
        $user->status = $request['status'];
        $result = $user->save();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function assignPickupMan($request)
    {
        DB::beginTransaction();
        try{
            $parcel                       = Parcel::find($request->id);
            $parcel->status               = 'pickup-assigned';
            $parcel->pickup_man_id        = $request->pickup_man;
            $parcel->pickup_fee           = DeliveryMan::find($request->pickup_man)->pick_up_fee;
            $parcel->save();

            if ($request->notify_pickup_man == 'notify'):
                $sms_body = $parcel->pickupMan->user->first_name.', a pickup has been assigned to you. Address: '.$parcel->pickup_address.', Phone number: '.$parcel->pickup_shop_phone_number.', Pickup date: '.$parcel->pickup_date;
                $this->smsSender('notify_pickup_man', $parcel->pickupMan->phone_number, $sms_body, true);
            endif;

            $this->parcelEvent($parcel->id, 'assign_pickup_man_event', '', $request->pickup_man, '', $request->note);
            $this->reverseReturn($parcel->parcel_no);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function assignDeliveryMan($request)
    {
        DB::beginTransaction();
        try{
            $parcel = Parcel::find($request->id);
            $parcel->status        = 'delivery-assigned';
            $parcel->delivery_man_id        = $request->delivery_man;
            $parcel->third_party_id         = $request->third_party != '' ?  $request->third_party : null;
            $parcel->delivery_fee           = DeliveryMan::find($request->delivery_man)->delivery_fee;
            $parcel->save();

            $this->parcelEvent($parcel->id, 'assign_delivery_man_event', $request->delivery_man, '', '', $request->note);
            $this->reverseReturn($parcel->parcel_no);
            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function parcelStatusUpdate($id, $status, $note, $hub = null, $delivery_man = null)
    {

        DB::beginTransaction();
         try{

            $parcel = Parcel::find($id);
            if($status == 'cancel'){
                $parcel->status_before_cancel        = $parcel->status;
            }
            if($status == 'received'){
                $parcel->hub_id        = $hub;
            }
            if($status == 'transferred-to-hub'){
                $parcel->transfer_to_hub_id         = $hub;
                $parcel->transfer_delivery_man_id   = $delivery_man;
            }
            if($status == 'transferred-received-by-hub'){
                $parcel->hub_id              = $hub;
                $parcel->transfer_to_hub_id  = null;
            }
            if($status == 're-request'){
                $parcel->status        = $parcel->status_before_cancel;
            }else{
                $parcel->status        = $status;
            }
            $parcel->date = date('Y-m-d');

            $this->accounts->incomeExpenseManage($id, $status);

            if($status == 'cancel'){
                $this->parcelEvent($parcel->id, 'parcel_cancel_event', '', '', '', $note);
            }elseif($status == 'received-by-pickup-man'){
                $this->parcelEvent($parcel->id, 'parcel_received_by_pickup_man_event', '', '', '', $note);
            }elseif($status == 'received'){
                $this->parcelEvent($parcel->id, 'parcel_received_event', '', '', '', $note,'', $hub);
            }elseif($status == 'transferred-to-hub'){
                $this->parcelEvent($parcel->id, 'parcel_transferred_to_hub_assigned_event', '', '', '', $note,'', $hub, $delivery_man);
            }elseif($status == 'transferred-received-by-hub'){
                $this->parcelEvent($parcel->id, 'parcel_transferred_to_hub_event', '', '', '', $note,'', $hub);
            }elseif($status == 'returned-to-greenx'){
                $this->parcelEvent($parcel->id, 'parcel_return_to_greenx_event', '', '', '', $note);
            }elseif($status == 'delivered'){
                $parcel->otp = rand(1000,9999);
                $parcel->delivered_date = date('Y-m-d H:i:s');
                $this->parcelEvent($parcel->id, 'parcel_delivered_event', '', '', '', $note);

                //sending delivery confirm otp to customer

                $sms_template = CustomerParcelSmsTemplates::where('subject','delivery_confirm_otp')->first();

                if($sms_template->sms_to_customer):
                    $sms_body = str_replace('{merchant_name}', $parcel->merchant->company, $sms_template->content);
                    $sms_body = str_replace('{parcel_id}', $parcel->parcel_no, $sms_body);
                    $sms_body = str_replace('{otp}', $parcel->otp, $sms_body);
                    $sms_body = str_replace('{our_company_name}', __('app_name'), $sms_body);
                    $this->smsSender('delivery_confirm_otp', $parcel->customer_phone_number, $sms_body, $sms_template->masking);
                endif;
            }elseif($status == 'returned-to-merchant'){
                $this->parcelEvent($parcel->id, 'parcel_returned_to_merchant_event', '', '', '', $note);
            }elseif($status == 're-request'){
                $this->parcelEvent($parcel->id, 'parcel_re_request_event', '', '', '', $note);
            }

            $parcel->save();

            $this->reverseReturn($parcel->parcel_no);
            DB::commit();
            return true;

         } catch (\Exception $e) {
             DB::rollback();
             return false;
         }
    }
    public function parcelCancel($request)
    {
        DB::beginTransaction();
        try{

            // $this->accounts->incomeExpenseManageCancel($request->id, 'cancel');

            $parcel                         = Parcel::find($request->id);
            $parcel->status_before_cancel   = $parcel->status;
            $parcel->status                 = 'cancel';
            $parcel->save();

            $note = __($request->predefined_reason).' '.$request->cancel_note;

            $this->parcelEvent($parcel->id, 'parcel_cancel_event', '', '', '', $note);
            $this->reverseReturn($parcel->parcel_no);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function deliveryReverse($request)
    {
        DB::beginTransaction();
        try{

            $this->accounts->incomeExpenseManageReverse($request->id, $request->status);

            $parcel                         = Parcel::find($request->id);

            // get previous for reverse process
            $previous_status                = $parcel->status;
            //end
            $parcel->status                 = $request->status;
            $parcel->date                   = date('Y-m-d');

            $previously_partially_delivered = false;

            if (($previous_status == 'partially-delivered' || $previous_status == 'returned-to-greenx' || $previous_status == 'return-assigned-to-merchant'
                || $previous_status == 'returned-to-merchant') && $parcel->is_partially_delivered && ($request->status == 'pending' || $request->status == 'pickup-assigned'
                || $request->status == 'received-by-pickup-man' || $request->status == 'received' || $request->status == 'transferred-received-by-hub' || $request->status == 'delivery-assigned')):

                if (number_format($parcel->price_before_delivery, 2, '.', '') != number_format($parcel->price, 2, '.', '')):
                    // cod charge define by location
                    $location            = $parcel->location;

                    // Start Charge calculate
                    $merchant              = $this->merchants->get($parcel->merchant_id);
                    $charge                = data_get($merchant->charges, $parcel->weight.'.'.$parcel->parcel_type);
                    $cod_charge            = data_get($merchant->cod_charges, $location);
                    $vat                   = $merchant->vat ?? 0.00;
                    $total_delivery_charge = $charge + $parcel->packaging_charge + $parcel->fragile_charge + $parcel->price_before_delivery / 100 * $cod_charge;
                    $total_delivery_charge += $total_delivery_charge / 100 * $vat;

                    $payable               = $parcel->price_before_delivery - number_format($total_delivery_charge, 2);
                    // End charge calculate

                    // if( number_format(number_format($total_delivery_charge, 2, '.', '') + number_format($payable, 2, '.', ''), 2, '.', '')  != number_format($parcel->price_before_delivery, 2, '.', '')){
                    //     $total_delivery_charge = $total_delivery_charge - 0.01;
                    // }

                    $parcel->price                  = $parcel->price_before_delivery;

                    // Charge
                    $parcel->charge                = $charge;
                    $parcel->cod_charge            = $cod_charge;
                    $parcel->vat                   = $vat;
                    $parcel->total_delivery_charge = floor($total_delivery_charge);
                    $parcel->payable               = ceil($payable);
                    // End charge
                endif;
                $previously_partially_delivered = true;
                $parcel->is_partially_delivered = false;
            endif;

            $parcel->save();

           //if previous delivery reverse, reverse that also
            $previous_reverse = ParcelEvent::where('parcel_id', $request->id)->where('title','delivery_reverse_event')->latest()->first();

            if(!blank($previous_reverse)):
                $previous_reverse->reverse_status = 'reversed';
                $previous_reverse->save();
            endif;

            if($previous_status == 'pickup-assigned'):
                foreach ($parcel->events as $event):
                    $event->reverse_status = 'reversed';
                    $event->save();
                endforeach;

                $title = 'parcel_create_event';

            elseif($previous_status == 'deleted'):

                foreach ($parcel->events as $event):
                    $event->reverse_status = 'reversed';
                    $event->save();
                endforeach;

                $title = 'parcel_pending_event';


            elseif($previous_status == 'received-by-pickup-man' || $previous_status == 're-schedule-pickup'):

                if($previous_status == 'received-by-pickup-man'):
                    $received = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_received_by_pickup_man_event')->latest()->first();
                    $received->reverse_status = 'reversed';
                    $received->save();
                endif;

                $title = 'parcel_received_by_pickup_man_event';

                if ($request->status == 'pickup-assigned' || $request->status == 'pending'):
                    $title = $this->requestPickupPending($request);
                endif;

            elseif($previous_status == 'received'):
                $title = $this->uptoReceived($request);
            elseif($previous_status == 'transferred-to-hub'):
                $title = $this->uptoTransfer($request);
            elseif($previous_status == 'transferred-received-by-hub'):
                $title = $this->uptoTransfer($request);
            elseif ($previous_status == 'delivery-assigned' || $previous_status == 're-schedule-delivery'):
                $reschedule_events = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_re_schedule_delivery_event')->latest()->get();
                foreach ($reschedule_events as $event):
                    $event->reverse_status = 'reversed';
                    $event->save();
                endforeach;
                $title = 'assign_delivery_man_event';
                $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','assign_delivery_man_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();

                if ($request->status == 'transferred-received-by-hub' || $request->status == 'transferred-to-hub' ||
                    $request->status == 'received' || $request->status == 'received-by-pickup-man' ||
                    $request->status == 'pickup-assigned' || $request->status == 'pending'):

                    $parcel->third_party_id = null;
                    $parcel->save();

                    $title = $this->uptoTransfer($request);
                endif;

            elseif ($previous_status == 'partially-delivered'):
                $title = $this->uptoPartialDelivery($request);
            elseif ($previous_status == 'returned-to-greenx' && $previously_partially_delivered):
                //reversing previous 'parcel_return_to_greenx_event'
                $received = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_return_to_greenx_event')->latest()->first();
                $received->reverse_status = 'reversed';
                $received->save();

                $title = $this->uptoPartialDelivery($request);
            elseif ($previous_status == 'return-assigned-to-merchant' && $previously_partially_delivered):
                $title = $this->returnAssignToMerchantPartialEvent($request);

            elseif ($previous_status == 'returned-to-merchant' && $previously_partially_delivered):
                $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_returned_to_merchant_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();

                if ($request->status == 'pending' || $request->status == 'pickup-assigned' || $request->status == 'received-by-pickup-man'
                    || $request->status == 'received' || $request->status == 'transferred-received-by-hub' || $request->status == 'delivery-assigned'
                    || $request->status == 'partially-delivered' || $request->status == 'partially-delivered' || $request->status == 'return-assigned-to-merchant'):

                    $title = $this->returnAssignToMerchantPartialEvent($request);
                endif;

            //end

            elseif ($previous_status == 'delivered' || $previous_status == 'returned-to-greenx'):
                if ($previous_status == 'delivered'):
                    //reversing previous upto 'delivery-assigned' and insert 'assign_delivery_man_event'
                    $received = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_delivered_event')->latest()->first();
                    $received->reverse_status = 'reversed';
                    $received->save();
                else:
                    //reversing previous 'parcel_return_to_greenx_event'
                    $received = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_return_to_greenx_event')->latest()->first();
                    $received->reverse_status = 'reversed';
                    $received->save();
                    //end reverse 'parcel_return_to_greenx_event'
                endif;

                if ($request->status != 'returned-to-greenx' && $request->status != 'delivered'):
                    $reschedule_events = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_re_schedule_delivery_event')->latest()->get();
                    foreach ($reschedule_events as $event):
                        $event->reverse_status = 'reversed';
                        $event->save();
                    endforeach;

                    $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','assign_delivery_man_event')->latest()->first();
                    $parcel_event->reverse_status = 'reversed';
                    $parcel_event->save();

                    $title = 'assign_delivery_man_event';
                    // upto delivery-assigned reversed and insert 'assign_delivery_man_event' end

                    if ($request->status == 'transferred-received-by-hub' || $request->status == 'transferred-to-hub' ||
                        $request->status == 'received' || $request->status == 'received-by-pickup-man' ||
                        $request->status == 'pickup-assigned' || $request->status == 'pending'):

                        $title = $this->uptoTransfer($request);
                    endif;

                elseif ($request->status == 'returned-to-greenx' || $request->status == 'delivered'):
                    if ($request->status == 'returned-to-greenx'):
                        $title = 'parcel_return_to_greenx_event';
                    else:
                        $title = 'parcel_delivered_event';
                    endif;
                endif;
            elseif ($previous_status == 'return-assigned-to-merchant'):
                $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_return_assign_to_merchant_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();

                //reverse and re-insert
                $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_return_to_greenx_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();

                $title = 'parcel_return_to_greenx_event';
                //end

                if ($request->status == 'delivery-assigned' || $request->status == 'transferred-received-by-hub' || $request->status == 'transferred-to-hub' || $request->status == 'received' || $request->status == 'received-by-pickup-man' || $request->status == 'pickup-assigned' || $request->status == 'pending'):
                    $title = $this->uptoDeliveryAssigned($request);
                endif;
            elseif ($previous_status == 'returned-to-merchant'):
                $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_returned_to_merchant_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();

                //reverse and re-insert
                $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_return_assign_to_merchant_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();

                $title = 'parcel_return_assign_to_merchant_event';
                //end

                if ($request->status == 'returned-to-greenx' || $request->status == 'delivery-assigned' || $request->status == 'transferred-received-by-hub' || $request->status == 'transferred-to-hub' || $request->status == 'received' || $request->status == 'received-by-pickup-man' || $request->status == 'pickup-assigned' || $request->status == 'pending'):
                    $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_return_to_greenx_event')->latest()->first();
                    $parcel_event->reverse_status = 'reversed';
                    $parcel_event->save();

                    $title = 'parcel_return_to_greenx_event';

                    if ($request->status == 'delivery-assigned' || $request->status == 'transferred-received-by-hub' || $request->status == 'transferred-to-hub' ||  $request->status == 'received' || $request->status == 'received-by-pickup-man' || $request->status == 'pickup-assigned' || $request->status == 'pending' ):
                        $title = $this->uptoDeliveryAssigned($request);
                    endif;
                endif;
            endif;

            $this->parcelEvent($parcel->id, 'delivery_reverse_event', '', '', '',$request->note);
            $this->parcelEvent($parcel->id, $title, @$parcel->delivery_man_id, @$parcel->pickup_man_id, @$parcel->return_delivery_man_id,'','reverse');
            $this->reverseReturn($parcel->parcel_no);
            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function requestPending($id)
    {
        $parcel_event = ParcelEvent::where('parcel_id', $id)->where('title','parcel_create_event')->latest()->first();
        $parcel_event->reverse_status = 'reversed';
        $parcel_event->save();
        return 'parcel_create_event';
    }

    public function requestPickupPending($request)
    {
        $reschedule_events = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_re_schedule_pickup_event')->latest()->get();
        foreach ($reschedule_events as $event):
            $event->reverse_status = 'reversed';
            $event->save();
        endforeach;

        $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','assign_pickup_man_event')->latest()->first();
        $parcel_event->reverse_status = 'reversed';
        $parcel_event->save();

        $title = 'assign_pickup_man_event';

        //if requested pending than reverse previous 'parcel_create_event'
        if ($request->status == 'pending'):
            $title = $this->requestPending($request->id);
        endif;

        return $title;
    }

    public function requestPickupManReceivedPickupPending($request)
    {
        $event = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_received_by_pickup_man_event')->latest()->first();
        if (!blank($event)):
            $event->reverse_status = 'reversed';
            $event->save();
        endif;

        $title = 'parcel_received_by_pickup_man_event';

        if ($request->status == 'pickup-assigned' || $request->status == 'pending'):
            $title = $this->requestPickupPending($request);
        endif;

        return $title;
    }

    public function uptoReceived($request)
    {
        //reverse received and insert received again
        $title = 'parcel_received_event';

        $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_received_event')->latest()->first();
        if(!blank($parcel_event)){
            $parcel_event->reverse_status = 'reversed';
            $parcel_event->save();
        }

        //end received

        if ($request->status == 'received-by-pickup-man' || $request->status == 'pickup-assigned' || $request->status == 'pending'):
            $title = $this->requestPickupManReceivedPickupPending($request);

            $parcel = $this->get($request->id);
            $parcel->hub_id = null;
            $parcel->save();
        endif;

        return $title;
    }

    public function uptoTransfer($request)
    {
        $events = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_transferred_to_hub_event')->latest()->get();

        foreach ($events as $event):
            $event->reverse_status = 'reversed';
            $event->save();
        endforeach;
        $title = 'parcel_transferred_to_hub_event';
        if ($request->status == 'transferred-to-hub'||
            $request->status == 'received' || $request->status == 'received-by-pickup-man' ||
            $request->status == 'pickup-assigned' || $request->status == 'pending'):

            $events = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_transferred_to_hub_assigned_event')->latest()->get();
            foreach ($events as $event):
                $event->reverse_status = 'reversed';
                $event->save();
            endforeach;

            $title = 'parcel_transferred_to_hub_assigned_event';
            if ($request->status == 'received' || $request->status == 'received-by-pickup-man' ||
                $request->status == 'pickup-assigned' || $request->status == 'pending'):

                $parcel = $this->get($request->id);
                $parcel->transfer_to_hub_id         = null;
                $parcel->transfer_delivery_man_id   = null;
                $parcel->save();

                $title = $this->uptoReceived($request);

            endif;

        endif;

        return $title;
    }

    public function uptoDeliveryAssigned($request)
    {
        $reschedule_events = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_re_schedule_delivery_event')->latest()->get();
        foreach ($reschedule_events as $event):
            $event->reverse_status = 'reversed';
            $event->save();
        endforeach;

        $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','assign_delivery_man_event')->latest()->first();
        if(!blank($parcel_event)){
            $parcel_event->reverse_status = 'reversed';
            $parcel_event->save();
        }

        //reverse delivery-assigned and insert delivery-assigned again
        $title = 'assign_delivery_man_event';

        if ($request->status == 'transferred-received-by-hub' || $request->status == 'transferred-to-hub' ||
            $request->status == 'received' || $request->status == 'received-by-pickup-man' ||
            $request->status == 'pickup-assigned' || $request->status == 'pending'):
            $title = $this->uptoTransfer($request);
        endif;

        return $title;
    }

    public function uptoPartialDelivery($request)
    {
        $received = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_partial_delivered_event')->latest()->first();
        $received->reverse_status = 'reversed';
        $received->save();

        $title = 'parcel_partial_delivered_event';

        if ($request->status == 'pending' || $request->status == 'pickup-assigned'
            || $request->status == 'received-by-pickup-man' || $request->status == 'received' || $request->status == 'transferred-received-by-hub' || $request->status == 'delivery-assigned'):
            $reschedule_events = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_re_schedule_delivery_event')->latest()->get();
            foreach ($reschedule_events as $event):
                $event->reverse_status = 'reversed';
                $event->save();
            endforeach;

            $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','assign_delivery_man_event')->latest()->first();
            $parcel_event->reverse_status = 'reversed';
            $parcel_event->save();

            $title = 'assign_delivery_man_event';
            // upto delivery-assigned reversed and insert 'assign_delivery_man_event' end

            if ($request->status == 'transferred-received-by-hub' || $request->status == 'transferred-to-hub' ||
                $request->status == 'received' || $request->status == 'received-by-pickup-man' ||
                $request->status == 'pickup-assigned' || $request->status == 'pending'):

                $title = $this->uptoTransfer($request);
            endif;
        endif;

        return $title;
    }

    public function returnAssignToMerchantPartialEvent($request)
    {
        $parcel_event = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_return_assign_to_merchant_event')->latest()->first();
        $parcel_event->reverse_status = 'reversed';
        $parcel_event->save();

        $title = 'parcel_return_assign_to_merchant_event';

        if ($request->status == 'pending' || $request->status == 'pickup-assigned' || $request->status == 'received-by-pickup-man'
            || $request->status == 'received' || $request->status == 'transferred-received-by-hub' || $request->status == 'delivery-assigned'
            || $request->status == 'partially-delivered' || $request->status == 'returned-to-greenx'):

            //reversing previous 'parcel_return_to_greenx_event'
            $received = ParcelEvent::where('parcel_id', $request->id)->where('title','parcel_return_to_greenx_event')->latest()->first();
            $received->reverse_status = 'reversed';
            $received->save();

            $title ='parcel_return_to_greenx_event';

            if ($request->status == 'pending' || $request->status == 'pickup-assigned'
                || $request->status == 'received-by-pickup-man' || $request->status == 'received' || $request->status == 'transferred-received-by-hub' || $request->status == 'delivery-assigned'
                || $request->status == 'partially-delivered'):
                $title = $this->uptoPartialDelivery($request);
            endif;
        endif;

        return $title;
    }

    public function reSchedulePickupMan($request)
    {
        DB::beginTransaction();
        try{
            $parcel                     = Parcel::find($request->id);
            $parcel->status             = 're-schedule-pickup';
            $parcel->pickup_date        = date('Y-m-d', strtotime($request->date));
            $parcel->pickup_time        = date('h:i:s', strtotime($request->time));
            $parcel->pickup_man_id      = $request->pickup_man;
            $parcel->pickup_fee         = DeliveryMan::find($request->pickup_man)->pick_up_fee;

            $parcel->save();

            if ($request->notify_pickup_man == 'notify'):
                $sms_body = $parcel->pickupMan->user->first_name.', a pickup has been re-scheduled and assigned to you. Address: '.$parcel->pickup_address.', Phone number: '.$parcel->pickup_shop_phone_number.', Pickup date: '.$parcel->pickup_date;
                $this->smsSender('notify_pickup_man', $parcel->pickupMan->phone_number, $sms_body, true);
            endif;

            $note = __($request->predefined_reason).' '.$request->note;

            $this->parcelEvent($parcel->id, 'parcel_re_schedule_pickup_event', '', $request->pickup_man, '',$note);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
    public function reScheduleDeliveryMan($request)
    {
        DB::beginTransaction();
            try{
            $parcel                     = Parcel::find($request->id);
            $parcel->status             = 're-schedule-delivery';
            $parcel->delivery_date        = date('Y-m-d', strtotime($request->date));
            $parcel->delivery_time        = date('h:i:s', strtotime($request->time));
            // $parcel->delivery_man_id      = $request->delivery_man;
            // $parcel->delivery_fee         = DeliveryMan::find($request->delivery_man)->delivery_fee;
            // $parcel->third_party_id       = $request->third_party != '' ?  $request->third_party : null;
            $parcel->save();

            $note = __($request->predefined_reason).' '.$request->note;

            $this->parcelEvent($parcel->id, 'parcel_re_schedule_delivery_event', $request->delivery_man, '', '', $note);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
    public function returnAssignToMerchant($request)
    {
        DB::beginTransaction();
        try{
            $parcel                              = Parcel::find($request->id);
            $parcel->status                      = 'return-assigned-to-merchant';
            $parcel->return_delivery_man_id      = $request->delivery_man;
            $parcel->return_fee                  = DeliveryMan::find($request->delivery_man)->return_fee;
            $parcel->save();

            $this->parcelEvent($parcel->id, 'parcel_return_assign_to_merchant_event', '', '', $request->delivery_man, $request->note);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function reSchedulePickup($request)
    {
        $delivery_men = DeliveryMan::with(['user' => function($query) {
            $query->where('status', 1);
        }])->get();

        $parcel = $this->get($request->id);

        $options = "<option value=''>". __('select_pickup_man') ."</option>";
        foreach($delivery_men as $delivery_man):
            if($parcel->pickup_man_id == $delivery_man->id){
                $options .= "<option value='$delivery_man->id' selected>". @$delivery_man->user->first_name.' '. @$delivery_man->user->last_name ."</option>";
            }
        endforeach;

        $data[1] = $options;
        $data[2] = date('m/d/Y', strtotime($parcel->pickup_date));
        $data[3] = date('h:i a', strtotime($parcel->pickup_time));
        $data[4] = $parcel->parcel_type;
        $data[5] = $parcel->events->whereIn('title',['assign_pickup_man_event','parcel_re_schedule_pickup_event'])->first()->cancel_note;
        return $data;
    }
    public function reScheduleDelivery($request)
    {
        $delivery_men = DeliveryMan::with(['user' => function($query) {
            $query->where('status', 1);
        }])->get();
        $parcel = $this->get($request->id);

        $options = "<option value=''>". __('select_delivery_man') ."</option>";
        foreach($delivery_men as $delivery_man):
            if($parcel->delivery_man_id == $delivery_man->id){
                $options .= "<option value='$delivery_man->id' selected>". $delivery_man->user->first_name.' '. $delivery_man->user->last_name ."</option>";
            }
        endforeach;

        $data[1] = $options;
        $data[2] = date('m/d/Y', strtotime($parcel->delivery_date));
        $data[3] = date('h:i a', strtotime($parcel->delivery_time));
        $data[4] = $parcel->parcel_type;
        $data[5] = $parcel->events->whereIn('title',['assign_delivery_man_event','parcel_re_schedule_delivery_event'])->first()->cancel_note;

        $third_party_options = "<option value=''>". __('select_delivery_man') ."</option>";

        if ($parcel->third_party_id != null):
            $third_party = ThirdParty::find($parcel->third_party_id);
            $third_party_options .= "<option value='$third_party->id' selected>". $third_party->name.' ('. $third_party->address .")</option>";
        endif;

        $data[6] = $parcel->location;
        $data[7] = $third_party_options;
        return $data;
    }

    public function parcelEvent($parcel_id, $title, $delivery_man = '', $pickup_man = '', $return_delivery_man = '', $cancel_note = '', $status = '', $hub = null, $transfer_delivery_man = null,$created_at = '',$additional_info="")
    {
        //dd($additional_info);
        $parcel = $this->get($parcel_id);
        $parcel_event                      = new ParcelEvent();

        if($title == 'delivery_reverse_event' || $title == 'cancel_reverse_event' || $status == 'reverse'):

            $parcel_event->parcel_id           = $parcel_id;
            $parcel_event->delivery_man_id     = $parcel->delivery_man_id;
            $parcel_event->pickup_man_id       = $parcel->pickup_man_id;
            $parcel_event->user_id             = Sentinel::getUser()->id;
            $parcel_event->title               = $title;
            $parcel_event->cancel_note         = $cancel_note;
            $parcel_event->hub_id              = $hub ?? $parcel->hub_id;
            $parcel_event->third_party_id      = $parcel->third_party_id;
            $parcel_event->transfer_delivery_man_id   = $transfer_delivery_man ?? $parcel->transfer_delivery_man_id;
            $parcel_event->save();

            return true;
        else:
            $parcel_event->parcel_id           = $parcel_id;
            $parcel_event->delivery_man_id     = $parcel->delivery_man_id;
            $parcel_event->pickup_man_id       = $parcel->pickup_man_id;
            if ($created_at != ''):
                $parcel_event->user_id             = 2260;
            else:
                $parcel_event->user_id             = Sentinel::getUser()->id;
            endif;
            $parcel_event->title               = $title;
            $parcel_event->cancel_note         = $cancel_note;
            $parcel_event->hub_id              = $hub ?? $parcel->hub_id;
            $parcel_event->third_party_id      = $parcel->third_party_id;
            $parcel_event->transfer_delivery_man_id   = $transfer_delivery_man ?? $parcel->transfer_delivery_man_id;
            if($additional_info !=NULL && !empty($additional_info)):
                $parcel_event->additional_info      = json_encode($additional_info);
            endif;

            if ($created_at != ''):
                $parcel_event->created_at = $created_at;
            endif;
        endif;

        $delivery_person = DeliveryMan::where('id',$parcel->delivery_man_id)->first();
        $pickup_person   = DeliveryMan::where('id',$parcel->pickup_man_id)->first();

         // merchant sms start
         $sms_template = SmsTemplate::where('subject',$title)->first();
         if (!blank($sms_template)):
             if($sms_template->sms_to_merchant):
                 $sms_body = str_replace('{merchant_name}', $parcel->merchant->company, $sms_template->content);
                 $sms_body = str_replace('{parcel_id}', $parcel->parcel_no, $sms_body);
                 $sms_body = str_replace('{pickup_date_time}',  date('M d, Y', strtotime($parcel->pickup_date)), $sms_body);
                 $sms_body = str_replace('{re_pickup_date_time}', date('M d, Y', strtotime($parcel->pickup_date)), $sms_body);
                 $sms_body = str_replace('{delivery_date_time}', date('M d, Y', strtotime($parcel->delivery_date)), $sms_body);
                 $sms_body = str_replace('{re_delivery_date_time}', date('M d, Y', strtotime($parcel->delivery_date)), $sms_body);
                 if ($created_at != ''):
                    $sms_body = str_replace('{current_date_time}', date('M d, Y h:i a', strtotime($created_at)), $sms_body);
                 else:
                     $sms_body = str_replace('{current_date_time}', date('M d, Y h:i a'), $sms_body);
                 endif;
                 $sms_body = str_replace('{return_date_time}', date('M d, Y h:i a'), $sms_body);
                 $sms_body = str_replace('{our_company_name}', __('app_name'), $sms_body);
                 $sms_body = str_replace('{pickup_man_name}', @$pickup_person->user->first_name, $sms_body);
                 $sms_body = str_replace('{pickup_man_phone}', @$pickup_person->phone_number, $sms_body);
                 $sms_body = str_replace('{delivery_man_name}', @$delivery_person->user->first_name, $sms_body);
                 $sms_body = str_replace('{delivery_man_phone}', @$delivery_person->phone_number, $sms_body);
                 $sms_body = str_replace('{cancel_note}', @$parcel->cancelnote->cancel_note, $sms_body);
                 $sms_body = str_replace('{price}', @$parcel->price, $sms_body);
                 $sms_body = str_replace('{short_url}', @$parcel->short_url, $sms_body);
                 $sms_body = str_replace('{otp_code}', $parcel->merchant_otp, $sms_body);
                 $sms_body = str_replace('{invoice_no}', $parcel->customer_invoice_no, $sms_body);
                 $sms_body = str_replace('{total_parcel}',  1, $sms_body);
                 //send sms
                 $this->smsSender($title, $parcel->merchant->phone_number, $sms_body, $sms_template->masking);

             endif;
             //merchant sms end
         endif;

        //customer sms start
         if ($this->checkLocation($parcel, $title)):
             $customer_sms_template = CustomerParcelSmsTemplates::where('subject',$title)->first();
             if (!blank($customer_sms_template)):
                 if($customer_sms_template->sms_to_customer):
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
                     $sms_body = str_replace('{short_url}', @$parcel->short_url, $sms_body);
                     $sms_body = str_replace('{amount}', number_format($parcel->price,2), $sms_body);
                     $sms_body = str_replace('{charge}', number_format($parcel->total_delivery_charge,2), $sms_body);
                     $sms_body = str_replace('{otp}', $parcel->otp, $sms_body);
                     //send sms
                     $this->smsSender($title, $parcel->customer_phone_number, $sms_body, $customer_sms_template->masking);
                 endif;
                 //customer sms end
             endif;
         endif;

        if($delivery_man != ""):
            $parcel_event->delivery_man_id      = $delivery_man;
        endif;
        if($pickup_man != ""):
            $parcel_event->pickup_man_id      = $pickup_man;
        endif;
        if($return_delivery_man != ""):
            $parcel_event->return_delivery_man_id      = $return_delivery_man;
        endif;
        if($transfer_delivery_man != ""):
            $parcel_event->transfer_delivery_man_id      = $transfer_delivery_man;
        endif;
        $parcel_event->save();
        return $parcel_event;
    }

    public function checkLocation($parcel, $title){
        if($parcel->location == 'outside_dhaka' || $parcel->location == 'sub_city'):
            if ($title != 'assign_delivery_man_event'):
                return false;
            endif;
        endif;

        return true;
    }

    public function reverseUpdate($id, $status, $note= '')
    {
        DB::beginTransaction();
        try{

            // $this->accounts->incomeExpenseManageCancel($id, 'reverse-cancel');

            $parcel                      = Parcel::find($id);
            $reverse_type                = $parcel->status;
            $parcel->status              = $status;
            $parcel->date                = date('Y-m-d');
            $parcel->save();

            //if previous received reverse received also

            if($reverse_type == 'cancel'){
                $previous_cancel = ParcelEvent::where('parcel_id', $id)->where('title','parcel_cancel_event')->latest()->first();
                if(!blank($previous_cancel)):
                    $previous_cancel->reverse_status = 'reversed';
                    $previous_cancel->save();
                endif;
                $cancel_reverse_event = ParcelEvent::where('parcel_id', $id)->where('title','cancel_reverse_event')->latest()->first();
                if(!blank($cancel_reverse_event)):
                    $cancel_reverse_event->reverse_status = 'reversed';
                    $cancel_reverse_event->save();
                endif;

            }else{

                $previous_cancel = ParcelEvent::where('parcel_id', $id)->where('title','parcel_delete_event')->latest()->first();
                if(!blank($previous_cancel)):
                    $previous_cancel->reverse_status = 'reversed';
                    $previous_cancel->save();
                endif;
                $cancel_reverse_event = ParcelEvent::where('parcel_id', $id)->where('title','delete_reverse_event')->latest()->first();
                if(!blank($cancel_reverse_event)):
                    $cancel_reverse_event->reverse_status = 'reversed';
                    $cancel_reverse_event->save();
                endif;

            }

            if ($status == 'pending'):
                $parcel_event = ParcelEvent::where('parcel_id', $id)->where('title','parcel_create_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();
                $title = 'parcel_create_event';
            elseif ($status == 'pickup-assigned'):
                $parcel_event = ParcelEvent::where('parcel_id', $id)->where('title','assign_pickup_man_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();
                $title = 'assign_pickup_man_event';
            elseif ($status == 're-schedule-pickup'):
                $parcel_event = ParcelEvent::where('parcel_id', $id)->where('title','parcel_re_schedule_pickup_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();
                $title = 'parcel_re_schedule_pickup_event';
            elseif ($status == 'received-by-pickup-man'):
                $parcel_event = ParcelEvent::where('parcel_id', $id)->where('title','parcel_received_by_pickup_man_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();
                $title = 'parcel_received_by_pickup_man_event';
            elseif($status == 'received'):
                $parcel_event = ParcelEvent::where('parcel_id', $id)->where('title','parcel_received_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();
                $title = 'parcel_received_event';
            elseif($status == 'transferred-to-hub'):
                $parcel_event = ParcelEvent::where('parcel_id', $id)->where('title','parcel_transferred_to_hub_assigned_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();
                $title = 'parcel_transferred_to_hub_assigned_event';
            elseif($status == 'transferred-received-by-hub'):
                $parcel_event = ParcelEvent::where('parcel_id', $id)->where('title','parcel_transferred_to_hub_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();
                $title = 'parcel_transferred_to_hub_event';
            elseif($status == 'delivery-assigned'):
                $parcel_event = ParcelEvent::where('parcel_id', $id)->where('title','assign_delivery_man_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();
                $title = 'assign_delivery_man_event';
            elseif($status == 're-schedule-delivery'):
                $parcel_event = ParcelEvent::where('parcel_id', $id)->where('title','parcel_re_schedule_delivery_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();
                $title = 'parcel_re_schedule_delivery_event';
            elseif($status == 'returned-to-greenx'):
                $parcel_event = ParcelEvent::where('parcel_id', $id)->where('title','parcel_return_to_greenx_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();
                $title = 'parcel_return_to_greenx_event';
            elseif($status == 'return-assigned-to-merchant'):
                $parcel_event = ParcelEvent::where('parcel_id', $id)->where('title','parcel_return_assign_to_merchant_event')->latest()->first();
                $parcel_event->reverse_status = 'reversed';
                $parcel_event->save();
                $title = 'parcel_return_assign_to_merchant_event';
            endif;

            if($reverse_type == 'cancel'){
                $this->parcelEvent($parcel->id, 'cancel_reverse_event', '', '', '',$note);
            }else{
                $this->parcelEvent($parcel->id, 'delete_reverse_event', '', '', '',$note);
            }


            $this->parcelEvent($parcel->id, $title, @$parcel->delivery_man_id, @$parcel->pickup_man_id, @$parcel->return_delivery_man_id,'', 'reverse');

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    //partial delivery
    public function partialDelivery($request)
    {
        DB::beginTransaction();
        try {
            $parcel = $this->get($request->id);
            $parcel->price_before_delivery  = $parcel->price;

            if (number_format($parcel->price, 2, '.', '') != number_format($request->cod, 2, '.', '')):
                // cod charge define by location
                $location            = $parcel->location;

                // Start Charge calculate
                $merchant              = $this->merchants->get($parcel->merchant_id);
                $charge                = data_get($merchant->charges, $parcel->weight.'.'.$parcel->parcel_type);
                $cod_charge            = data_get($merchant->cod_charges, $location);
                $vat                   = $merchant->vat ?? 0.00;
                $total_delivery_charge = $charge + $parcel->packaging_charge + $parcel->fragile_charge + $request->cod / 100 * $cod_charge;
                $total_delivery_charge += $total_delivery_charge / 100 * $vat;

                $payable               = $request->cod - number_format($total_delivery_charge, 2);
                // End charge calculate

                // if( number_format(number_format($total_delivery_charge, 2, '.', '') + number_format($payable, 2, '.', ''), 2, '.', '')  != number_format($request->cod, 2, '.', '')){
                //     $total_delivery_charge = $total_delivery_charge - 0.01;
                // }

                // Charge
                $parcel->charge                = $charge;
                $parcel->cod_charge            = $cod_charge;
                $parcel->vat                   = $vat;
                $parcel->total_delivery_charge = floor($total_delivery_charge);
                $parcel->payable               = ceil($payable);
                // End charge

                $parcel->price                  = $request->cod;
            endif;
            $parcel->status                 = 'partially-delivered';
            $parcel->delivered_date = date('Y-m-d H:i:s');
            $parcel->is_partially_delivered =  true;

            $parcel->date                   = date('Y-m-d');
            $this->parcelEvent($parcel->id, 'parcel_partial_delivered_event', '', '', '', $request->note);
            $parcel->save();
            $this->accounts->incomeExpenseManage($parcel->id, $parcel->status);
            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
    //partial delivery ends

    public function customerDetails($request)
    {
        $parcel = $this->all()->where('customer_phone_number', $request->phone_number)->first();

        if (!blank($parcel)):
            $data['customer_name'] = $parcel->customer_name;
            $data['customer_address'] = $parcel->customer_address;
        else:
            $data['customer_name'] = '';
            $data['customer_address'] = '';
        endif;

        return $data;
    }

    public function generate_random_string($length=13) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function getThanaUnion($request)
    {
        $thana_unions = DistrictZila::where('district_name', $request->district)->get();

        return view('admin.parcel.get_thana_union', compact('thana_unions'))->render();
    }

    public function getDistrict()
    {
        $districts = DistrictZila::groupby('district_name')->distinct()->get();

        return view('admin.parcel.district', compact('districts'))->render();
    }

    public function createPaperflyParcel($request)
    {
        $selected_address   = DistrictZila::find($request->thana_union);
        $parcel             = $this->get($request->id);
        $response           = $this->createParcel($parcel, $selected_address);

        if ($response->response_code == 200):
            $parcel->tracking_number = $response->success->tracking_number;
            $parcel->save();

            return true;
        else:
            return $response->error->message;
        endif;
    }

    public function trackParcel($id)
    {
        $parcel             = $this->get($id);
        $response           = $this->trackPaperflyParcel($parcel);

        if ($response->response_code == 200):
            return true;
        else:
            return $response->error->message;
        endif;
    }


    public function parcelOtpGenerate($id)
    {
         $otp = rand(1000, 9999);
         $parcel = Parcel::find($id);
         if(!empty($parcel->id)){
            DB::beginTransaction();
            try{
                $parcel->merchant_otp = $otp;
                $parcel->save();
                DB::commit();
                $this->parcelEvent($id, 'parcel_otp_event', '', '', '', '');
                $data = [
                    'phone' => $parcel->merchant->phone_number,
                    'success'=> true
                ];
                return $data;
            }catch(\Exception $e){
                DB::rollback();
                $data = [
                    'success'=> false
                ];
                return $data;
            }
         }
    }


    public function otpCodevarified($parcel_id, $otp_code)
    {
        $parcel = Parcel::find($parcel_id);

        if($parcel->merchant_otp == $otp_code){
            return true;
        }else{
            return false;
        }
    }

    public function merchantReturnConfirm($id, $otp){

        $parcel = Parcel::find($id);
        if($parcel->merchant_otp == $otp)
        {
            return true;
        }else{
            return false;
        }
    }

    public function returnList()
    {

       return $returnParcls = BulkReturn::orderbydesc('id')
                                ->when(!hasPermission('return_read_all'), function($query){
                                        $query->where('user_id', Sentinel::getUser()->id);
                                })->paginate(\Config::get('greenx.paginate'));
    }

    public function returnListFilter($request)
    {
        $merchant_id = $request->merchant;
        $status = $request->status;
        $query = BulkReturn::query();
        $query->when(!hasPermission('return_read_all'), function($query){
                        $query->where('user_id', Sentinel::getUser()->id);
                });
        if($merchant_id !=''):
            $query->where('merchant_id', $merchant_id);
        endif;
        if($status !='all'):
            $query->where('status', $status);
        endif;
        return $query->paginate(\Config::get('greenx.paginate'));

    }

    public function searchMerchant($merchant_name)
    {
        return $returnParcls = BulkReturn::join('merchants as m', 'm.id', 'merchant_id')
        ->selectRaw("*, bulk_returns.status as bulk_status, bulk_returns.created_at as bulk_creatd_at, bulk_returns.updated_at as bulk_updated_at, m.id as merchant_id_no ")
        ->where('m.company', "like", "%". $merchant_name ."%")
        ->join('users as cr', 'cr.id', 'bulk_returns.user_id')
        ->join('users as pr', 'cr.id', 'bulk_returns.processed_by')
        ->get();

    }

    public function assignList($id)
    {
        return $returnParcls= DB::table('parcel_returns as pr')
        ->selectRaw('m.company,  pr.merchant_id, p.parcel_no, m.address, u.first_name, u.last_name, p.customer_invoice_no, p.id, m.phone_number, m.address, dm.phone_number, dm.address')
        ->join('merchants as m', 'm.id', 'pr.merchant_id')
        ->join('delivery_men as dm', 'dm.id', 'pr.return_man_id')
        ->join('users as u', 'u.id', 'dm.user_id')
        ->join('parcels as p', 'p.parcel_no', 'pr.parcel_no')
        ->where('pr.status', 'return-assigned-to-merchant')
        ->where('pr.merchant_id', $id)
        ->groupBy('pr.merchant_id')
        ->get();
    }

    public function bulkOtpCode($request)
    {
        $merchant_id = $request->merchant_id;
        $batch_no = $request->batch_no;
        //$parcels = Parcel::where('merchant_id', $merchant_id)->where('status', 'return-assigned-to-merchant')->get();
        $parcels = DB::table("parcel_returns as pr")->select('p.parcel_no', 'p.customer_invoice_no')
                    ->join('parcels as p', 'p.parcel_no', '=', 'pr.parcel_no')
                    ->where('pr.batch_no', $batch_no)->where('pr.status', "!=", 'reversed')->get();

        $otp = rand(1000, 9999);
         if(count($parcels) > 0){
            DB::beginTransaction();
            try{
                $parcel_no=array();
                $invoice_no=array();
                foreach($parcels as $parcel)
                {
                    $parcel_info= Parcel::where('merchant_id', $merchant_id)->where('parcel_no', $parcel->parcel_no)->where('status', 'return-assigned-to-merchant')->first();
                    $parcel_info->merchant_otp = $otp;
                    $parcel_info->save();
                    array_push($parcel_no, $parcel->parcel_no);
                    array_push($invoice_no, $parcel->customer_invoice_no);
                    $parcel_event                           = new ParcelEvent();
                    $parcel_event->parcel_id                = $parcel_info->id;
                    $parcel_event->return_delivery_man_id   = $parcel_info->return_delivery_man_id;
                    $parcel_event->user_id                  = Sentinel::getUser()->id;
                    $parcel_event->title                    = 'parcel_otp_event';
                    $parcel_event->save();

                }
                 $parcel_no_list =  implode(",",  $parcel_no);
                 $invoice_no_list = implode(",",  $invoice_no);
                 $total_parcel = count($parcels);
                DB::commit();
                 $merchant = Merchant::find($merchant_id);

                  $this->bulkparcelReturnEvent("parcel_otp_event", $otp,  $merchant->phone_number,  $parcel_no_list, $invoice_no_list, $total_parcel);


                $data = [
                    'phone' => $merchant->phone_number,
                    'success'=> true
                ];
                return $data;
            }catch(\Exception $e){
                DB::rollback();
                $data = [
                    'success'=> false,
                    'message'=>$e->getMessage()
                ];
                return $data;
            }
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

    public function bulkOtpCodeCheck($request)
    {
        $merchant_id = $request->merchant_id;
        $otp_code = $request->otp_code;
        $batch_no = $request->batch_no;
        $parcels = DB::table("parcel_returns as pr")
                    ->join('parcels as p', 'p.parcel_no', '=', 'pr.parcel_no')
                    ->where('pr.batch_no', $batch_no)
                    ->where('p.merchant_otp', $otp_code)
                    ->where('p.merchant_id', $merchant_id)
                    ->count();

        if($parcels > 0)
        {
            return true;
        }else{
            return false;
        }

    }

    public function confirmBulkReturn($request)
    {
        $return_sms = SmsTemplate::where('subject','parcel_otp_event')->first();
         if(($return_sms->sms_to_merchant == 1 && $this->bulkOtpCodeCheck($request)) || $return_sms->sms_to_merchant == 0 ):
         $parcel_list = $request->parcel_id;
         $merchant_id  = $request->merchant_id;
         $batch_no = $request->batch_no;

            DB::beginTransaction();

            $parcel_no=array();
            $invoice_no=array();

            try{
                foreach($parcel_list as $data)
                {
                     $parcel = Parcel::find($data);

                     //parcel return update
                     $return_parcel = ParcelReturn::where('parcel_no', $parcel->parcel_no)
                                    ->where('status', 'return-assigned-to-merchant')
                                    ->where('batch_no', $batch_no)
                                    ->where('merchant_id', $merchant_id)
                                    ->first();
                     $return_parcel->status = "returned-to-merchant";
                     $return_parcel->return_date = date('Y-m-d');
                     $return_parcel->save();

                    //parcel_event
                    $parcel_event                      = new ParcelEvent();
                    $parcel_event->parcel_id           = $parcel->id;
                    $parcel_event->pickup_man_id       = $parcel->pickup_man_id;
                    $parcel_event->user_id             = \Sentinel::getUser()->id;
                    $parcel_event->title               = 'parcel_returned_to_merchant_event';
                    $parcel_event->save();

                    //parcel update
                    if($parcel->status == 'return-assigned-to-merchant' && $parcel->merchant_id == $merchant_id):
                        $parcel->status="returned-to-merchant";
                        $parcel->date = date('Y-m-d');
                        $parcel->save();
                        $this->accounts->incomeExpenseManage($parcel->id, 'returned-to-merchant');
                    endif;

                    array_push($parcel_no, $parcel->parcel_no);
                    array_push($invoice_no, $parcel->customer_invoice_no);

                }
                //bulk return update
                $bultReturn = BulkReturn::where('batch_no', $batch_no)->first();
                $bultReturn->status = "processed";
                $bultReturn->processed_by = \Sentinel::getUser()->id;
                $bultReturn->save();

                DB::commit();

                $parcel_no_list =  implode(",",  $parcel_no);
                $invoice_no_list = implode(",",  $invoice_no);
                $total_parcel = count($parcel_list);

                $this->bulkparcelReturnEvent("parcel_returned_to_merchant_event", '',  $parcel->merchant->phone_number,  $parcel_no_list, $invoice_no_list, $total_parcel);


              return true;

            }catch(Exception $e){
                DB::rollback();
                return false;
            }
        else:
        return false;

        endif;


    }

    public function reverseReturn($parcel_no)
    {
        $parcel_no = $parcel_no;
        $return_parcel = ParcelReturn::where('parcel_no', $parcel_no)->where('status', 'return-assigned-to-merchant')->first();
        if($return_parcel){
            $return_parcel->status= "reversed";
            $return_parcel->save();
            return true;
        }else{
            return true;
        }

    }

    public function parcelReturnReverse($request)
    {
        $parcel_id = $request->id;
        $parcel = Parcel::where('parcel_no', $parcel_id)->first();
        if(!empty($parcel))
        {
           if($this->parcelStatusUpdate($parcel->id, 'returned-to-greenx', $request->note)){
            return true;
           }else{
            return false;
           }
        }


    }

    public function parcelList($limit)
    {
        if(hasPermission('read_all_parcel')):
            $query = parcel::query();
            $query->whereIn('status', ['returned-to-greenx', 'return-assigned-to-merchant', 'partially-delivered', 'returned-to-merchant']);
            return $query->paginate($limit);
        else:
            return false;
        endif;
    }


    //delivery otp code script
    public function deliveryOtpGenerate($id, $title)
    {
         $otp = rand(1000, 9999);
         $parcel = Parcel::find($id);
         if(!empty($parcel->id)){
            DB::beginTransaction();
            try{
                $parcel->otp = $otp;
                $parcel->save();
                DB::commit();
                $this->parcelEvent($id, $title, '', '', '', '');
                $data = [
                    'phone' => $parcel->customer_phone_number,
                    'success'=> true
                ];
                return $data;
            }catch(\Exception $e){
                DB::rollback();
                $data = [
                    'success'=> false
                ];
                return $data;
            }
         }
    }




}
