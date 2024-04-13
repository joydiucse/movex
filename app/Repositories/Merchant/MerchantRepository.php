<?php

namespace App\Repositories\Merchant;
use App\Models\Charge;
use App\Models\CodCharge;
use App\Models\Image as ImageModel;
use App\Models\Merchant;
use App\Models\Account\MerchantAccount;
use App\Models\MerchantPaymentAccount;
use App\Models\Shop;
use App\Models\TempStore;
use App\Models\User;
use App\Repositories\Interfaces\Merchant\MerchantInterface;
use App\Traits\RandomStringTrait;
use App\Traits\SmsSenderTrait;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Image;
use Reminder;
use SoapClient;
use App\Models\NameChangeRequest;
use Exception;
use App\Models\NameSmsTemplate;
use App\Traits\EditLogTrait;
use App\Models\MerchantEditLog;
class MerchantRepository implements MerchantInterface{

    use SmsSenderTrait;
    use RandomStringTrait;
    use EditLogTrait;

    public function all()
    {
        return Merchant::get();
    }

    public function activeAll()
    {
        return Merchant::where('status', 1)->get();
    }

    public function paginate($limit)
    {
        return Merchant::with('user.image','parcels')
        ->when(!hasPermission('read_all_merchant'), function ($query){
            $query->whereHas('user',function ($q){
                $q->where('hub_id', \Sentinel::getUser()->hub_id)
                  ->orWhere('hub_id', null);
            });
        })->orderByDesc('id')->paginate($limit);

    }

    public function get($id)
    {
        return Merchant::find($id);
    }

    public function store($request)
    {

         DB::beginTransaction();
         try{
            if (!blank($request->file('image'))) {

                $requestImage           = $request->file('image');
                $fileType               = $requestImage->getClientOriginalExtension();

                $originalImage      = date('YmdHis') . "_original_" . rand(1, 50) . '.' . $fileType;
                $imageSmallOne      = date('YmdHis') . "image_small_one" . rand(1, 50) . '.' . $fileType;
                $imageSmallTwo      = date('YmdHis') . "image_small_two" . rand(1, 50) . '.' . $fileType;
                $imageSmallThree    = date('YmdHis') . "image_small_three" . rand(1, 50) . '.' . $fileType;

                $directory              = 'admin/profile-images/';

                if(!is_dir($directory)) {
                    mkdir($directory);
                }

                $originalImageUrl       = $directory . $originalImage;
                $imageSmallOneUrl       = $directory . $imageSmallOne;
                $imageSmallTwoUrl       = $directory . $imageSmallTwo;
                $imageSmallThreeUrl     = $directory . $imageSmallThree;

                Image::make($requestImage)->save($originalImageUrl, 80);
                Image::make($requestImage)->fit(32, 32)->save($imageSmallOneUrl, 80);
                Image::make($requestImage)->fit(40, 40)->save($imageSmallTwoUrl, 80);
                Image::make($requestImage)->fit(80, 80)->save($imageSmallThreeUrl, 80);

                $image                          = new ImageModel();
                $image->original_image          = $originalImageUrl;
                $image->image_small_one         = $imageSmallOneUrl;
                $image->image_small_two         = $imageSmallTwoUrl;
                $image->image_small_three       = $imageSmallThreeUrl;
                $image->save();

            }

            $user = new User();
            $user->first_name       = $request->first_name;
            $user->last_name        = $request->last_name;
            $user->email            = $request->email;
            $user->phone_number     = $request->phone_number;
            $user->password         = bcrypt($request->password);
            $user->permissions      = isset($request->permissions) ? $request->permissions : [];
            $user->user_type        = 'merchant';
            $user->is_primary       = 1;
            $user->hub_id           = $request->pickup_hub ? $request->pickup_hub : 1;
            $user->image_id         = $image->id ?? null;
            $user->save();

            $this->saveMerchant($request, $user->id);

            $activation = Activation::create($user);
            Activation::complete($user, $activation->code);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function update($request)
    {
       $merchant_old_data = [];
       $merchant_new_data = [];
        DB::beginTransaction();
        try{

            $merchant_old_data['user']  = User::find($request->id);
            $user = User::find($request->id);
            if (!blank($request->file('image'))) {

                $merchant_old_data['image']    = ImageModel::find($user->image_id);
                $image                         = ImageModel::find($user->image_id);
                if(!blank($image)):
                    if($image->original_image != "" && file_exists($image->original_image)):
                        unlink($image->original_image);
                    endif;
                    if($image->image_small_one != "" && file_exists($image->image_small_one)):
                        unlink($image->image_small_one);
                    endif;
                    if($image->image_small_two != "" && file_exists($image->image_small_two)):
                        unlink($image->image_small_two);
                    endif;
                    if($image->image_small_three != "" && file_exists($image->image_small_three)):
                        unlink($image->image_small_three);
                    endif;
                else:
                    $image     = new ImageModel();
                endif;

                $requestImage           = $request->file('image');
                $fileType               = $requestImage->getClientOriginalExtension();

                $originalImage          = date('YmdHis') . "_original_" . rand(1, 50) . '.' . $fileType;
                $imageSmallOne          = date('YmdHis') . "image_small_one" . rand(1, 50) . '.' . $fileType;
                $imageSmallTwo          = date('YmdHis') . "image_small_two" . rand(1, 50) . '.' . $fileType;
                $imageSmallThree        = date('YmdHis') . "image_small_three" . rand(1, 50) . '.' . $fileType;

                $directory              = 'admin/profile-images/';

                if(!is_dir($directory)) {
                    mkdir($directory);
                }

                $originalImageUrl       = $directory . $originalImage;
                $imageSmallOneUrl       = $directory . $imageSmallOne;
                $imageSmallTwoUrl       = $directory . $imageSmallTwo;
                $imageSmallThreeUrl     = $directory . $imageSmallThree;

                Image::make($requestImage)->save($originalImageUrl, 80);
                Image::make($requestImage)->fit(32, 32)->save($imageSmallOneUrl, 80);
                Image::make($requestImage)->fit(80, 80)->save($imageSmallTwoUrl, 80);
                Image::make($requestImage)->fit(80, 80)->save($imageSmallThreeUrl, 80);

                $image->original_image          = $originalImageUrl;
                $image->image_small_one         = $imageSmallOneUrl;
                $image->image_small_two         = $imageSmallTwoUrl;
                $image->image_small_three       = $imageSmallThreeUrl;
                $image->save();

                $user->image_id    = $image->id;

                $merchant_new_data['image'] = $image;
            }

            $user->first_name    = $request->first_name;
            $user->last_name     = $request->last_name;
            $user->email         = $request->email;
            $user->hub_id        = $request->pickup_hub ? $request->pickup_hub : 1;
            $user->phone_number     = $request->phone_number;
            if($request->password != ""):
                $user->password      = bcrypt($request->password);
            endif;
            $user->permissions      = isset($request->permissions) ? $request->permissions : [];
            $user->save();

            $merchant_new_data['user'] = $user;

            $this->updateMerchant($request, $merchant_old_data, $merchant_new_data);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function saveMerchant($request, $userId){

        $cod_charges = [];
        foreach($request->locations as $key=>$location){
            $cod_charges[$location] = $request->charge[$key];
        }

        $charges = [];
        foreach($request->weights as $key=>$weight){
            $charges[$weight] = [
                'same_day' => $request->same_day[$key],
                'next_day' => $request->next_day[$key],
                // 'frozen' => $request->frozen[$key],
                'sub_city' => $request->sub_city[$key],
                'outside_dhaka' => $request->outside_dhaka[$key],
                // 'third_party_booking' => $request->third_party_booking[$key]
            ];
        }

        $merchant = new Merchant();
        $merchant->user_id          = $userId;
        $merchant->company          = $request['company'];
        $merchant->vat              = $request['vat'] == '' ?  0.00 : $request['vat'];
        $merchant->phone_number     = $request['phone_number'];
        $merchant->city             = $request['city'];
        $merchant->zip              = $request['zip'];
        $merchant->address          = $request['address'];
        $merchant->website          = $request['website'];
        $merchant->billing_street   = $request['billing_street'];
        $merchant->billing_city     = $request['billing_city'];
        $merchant->billing_zip      = $request['billing_zip'];
        $merchant->nid              = $request->file('nid') ? $this->fileUpload($request->file('nid'), 'nid') : '';
        $merchant->trade_license           = $request->file('trade_license') ? $this->fileUpload($request->file('trade_license'), 'trade-license') : '';

        $merchant->api_key          = $this->generate_random_string(15);
        $merchant->secret_key       = $this->generate_random_string(30);

        $merchant->cod_charges        = $cod_charges;
        $merchant->charges            = $charges;
        $merchant->key_account_id     = $request['staff_id'];
        $merchant->sales_agent_id     = $request['sales_agent'];
        $merchant->save();

        $merchant_account                    = new MerchantAccount();
        $merchant_account->details           = 'opening_balance';
        $merchant_account->source            = 'opening_balance';
        $merchant_account->date              = date('Y-m-d');
        $merchant_account->type              = 'income';
        $merchant_account->amount            = $request['opening_balance'];
        $merchant_account->merchant_id       = $merchant->id;
        $merchant_account->save();

        $this->saveMerchantPaymentAccount($merchant->id);
        $this->saveMerchantShop($merchant->id, $request);
    }

    public function updateMerchant($request, $merchant_old_data=null, $merchant_new_data=null){
        $cod_charges = [];
        foreach($request->locations as $key=>$location){
            $cod_charges[$location] = $request->charge[$key];
        }

        $charges = [];
        foreach($request->weights as $key=>$weight){
            $charges[$weight] = [
                'same_day' => $request->same_day[$key],
                'next_day' => $request->next_day[$key],
                // 'frozen' => $request->frozen[$key],
                'sub_city' => $request->sub_city[$key],
                'outside_dhaka' => $request->outside_dhaka[$key],
                // 'third_party_booking' => $request->third_party_booking[$key]
            ];
        }

        $merchant_old_data['merchant'] = Merchant::find($request->merchant_id);
        $merchant = Merchant::find($request->merchant_id);


        $merchant_old_data['cod_charges'] = $merchant->cod_charges;
        $merchant_old_data['charges'] = $merchant->charges;

        if(hasPermission('merchant_company_name_update')){

            if($request['company'] !== $merchant->company){
                $this->nameChangeRequest("company", $merchant->company , $request['company'],  $merchant->id, null, "accept");
            }
            $merchant->company          = $request['company'];

        }else{
            $merchant->company          = $merchant->company;
        }

        $merchant->vat              = $request['vat'] == '' ?  0.00 : $request['vat'];
        $merchant->phone_number     = $request['phone_number'];
        $merchant->city             = $request['city'];
        $merchant->zip              = $request['zip'];
        $merchant->address          = $request['address'];
        $merchant->website          = $request['website'];
        $merchant->billing_street   = $request['billing_street'];
        $merchant->billing_city     = $request['billing_city'];
        $merchant->billing_zip      = $request['billing_zip'];

        //check if new nid file selected than replace
        if ($request->file('nid')):
            $this->removeOldFile($merchant->nid);
            $merchant->nid              = $this->fileUpload($request->file('nid'), 'nid');
        endif;
        //check if new trade license file selected than replace
        if ($request->file('trade_license')):
            $this->removeOldFile($merchant->trade_license);
            $merchant->trade_license           = $this->fileUpload($request->file('trade_license'), 'trade-license');
        endif;

        $merchant->cod_charges        = $cod_charges;
        $merchant->charges            = $charges;
        $merchant->key_account_id     = $request['staff_id'];
        $merchant->sales_agent_id     = $request['sales_agent'];

        $merchant->save();

        if ($merchant->merchantAccount->payment_withdraw_id == null && $merchant->merchantAccount->is_paid == false):
            $merchant_old_data['account']        = $merchant->merchantAccount;
            $merchant_account                    = $merchant->merchantAccount;
            $merchant_account->details           = 'opening_balance';
            $merchant_account->source            = 'opening_balance';
            $merchant_account->date              = date('Y-m-d');
            $merchant_account->type              = 'income';
            $merchant_account->amount            = $request['opening_balance'];
            $merchant_account->save();
            $merchant_new_data['account'] = $merchant_account;
        endif;

        $merchant_new_data['cod_charges'] = $cod_charges;
        $merchant_new_data['charges'] = $charges;
        $merchant_new_data['merchant'] = $merchant;


        $this->merchant_edit_log($merchant_old_data, $merchant_new_data, $merchant->id);

    }

    public function fileUpload($image, $type){

        $requestImage           = $image;
        $fileType               = $requestImage->getClientOriginalExtension();

        $original   = date('YmdHis') .'-'. $type . rand(1, 50) . '.' . $fileType;

        $directory              = 'admin/'.$type.'/';

        if(!is_dir($directory)) {
            mkdir($directory);
        }

        $originalFileUrl       = $directory . $original;

        if($fileType == 'pdf'):
            $requestImage->move($directory,$original);
        else:
            Image::make($requestImage)->save($originalFileUrl, 80);
        endif;

        return $originalFileUrl;
    }

    public function delete($id, $merchant)
    {
        DB::beginTransaction();
        try{

            $user  = User::find($id);
            $image = ImageModel::find($user->image_id);
            if(!blank($image)):
                if($image->original_image != "" && file_exists($image->original_image)):
                    unlink($image->original_image);
                endif;
                if($image->image_small_one != "" && file_exists($image->image_small_one)):
                    unlink($image->image_small_one);
                endif;
                if($image->image_small_two != "" && file_exists($image->image_small_two)):
                    unlink($image->image_small_two);
                endif;
                if($image->image_small_three != "" && file_exists($image->image_small_three)):
                    unlink($image->image_small_three);
                endif;
                $image->delete();
            else:
               $user->delete();
            endif;

            if(!blank($merchant->nid)):
                if($merchant->nid != "" && file_exists($merchant->nid)):
                    unlink($merchant->nid);
                endif;
            endif;

            if(!blank($merchant->trade_license)):
                if($merchant->trade_license != "" && file_exists($merchant->trade_license)):
                    unlink($merchant->trade_license);
                endif;
            endif;

            MerchantAccount::where('merchant_id', $merchant->id)->where('source', 'opening_balance')->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function removeOldFile($image)
    {
        if($image != "" && file_exists($image)):
            unlink($image);
        endif;
    }

    public function filter($request)
    {
        $query = Merchant::query();

        if(!hasPermission('read_all_merchant')){
            $query->whereHas('user',function ($q){
                $q->where('hub_id', \Sentinel::getUser()->hub_id)
                    ->orWhere('hub_id', null);
            });
        }

        if ($request->hub != 'all'){
            $query->whereHas('user', function ($q) use ($request){
                $q->when($request->hub == 'pending', function ($search){
                    $search->where('hub_id', null);
                })->when($request->hub != 'pending', function ($search) use ($request){
                    $search->where('hub_id', $request->hub);
                });
            });
        }

        if ($request->company_name != "") {
            $query->where('company', 'LIKE', "%{$request->company_name}%");
        }

        if ($request->approval_status != "any") {
            $query->where('registration_confirmed', $request->approval_status);
        }

        if ($request->status != "any") {
            $query->where('status', $request->status);
        }

        if ($request->sort_by == 'oldest_on_top'):
            $query->orderBy('id');
        elseif ($request->sort_by == 'newest_on_top'):
            $query->orderByDesc('id');
        else:
            $query->withCount(['parcels' => function (Builder $query) {
                $query->where(function ($query){
                    $query->whereIn('parcels.status', ['delivered','delivered-and-verified'])
                        ->orWhere('is_partially_delivered', true);
                });
            }])->orderBy('parcels_count', 'desc')->orderByDesc('id');
        endif;

        return $query->paginate(\Config::get('greenx.parcel_merchant_paginate'));

    }

    public function statusChange($request)
    {
        DB::beginTransaction();
            try{

            $merchant = Merchant::find($request['id']);
            $merchant->status = $request['status'];
            $merchant->save();

            $user = User::find($merchant->user_id);
            $user->status = $request['status'];
            $user->save();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function tempStore($data)
    {
        DB::beginTransaction();

        try{
            $temp = new TempStore();

            $data['phone_number'] = preg_replace('/^(\+88|88|-)/', '', $data['phone_number']);

            $temp->company = $data['company'];
            $temp->first_name = $data['first_name'];
            $temp->last_name = $data['last_name'];
            $temp->address = $data['address'];
            $temp->phone_number = $data['phone_number'];
            $temp->email = $data['email'];
            $temp->password = bcrypt($data['password']);
            $temp->otp = rand(10000 , 99999);
            $temp->ip  = \Request::ip();
            $temp->browser = $this->getBrowser(\Request::header('user-agent'));
            $temp->platform = $this->getPlatForm(\Request::header('user-agent'));
            $temp->user_agent = \Request::header('user-agent');

            $sms_body = __('hello').' '. $temp->first_name .', '. __('use').' '. $temp->otp .' '. __('to_verify_your_phone_number_on_greenx_ecourier');

            if($this->smsSender('otp', $temp->phone_number, $sms_body, false)):
                $temp->save();

                DB::commit();
                return $temp->id;
            else:
                return 'false';
            endif;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function getPlatForm($u_agent)
    {
        $platform = '';
        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
        return $platform;
    }
    public function getBrowser($u_agent)
    {
        $bname = '';
        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }elseif(preg_match('/Firefox/i',$u_agent)){
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }elseif(preg_match('/OPR/i',$u_agent)){
            $bname = 'Opera';
            $ub = "Opera";
        }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
            $bname = 'Apple Safari';
            $ub = "Safari";
        }elseif(preg_match('/Netscape/i',$u_agent)){
            $bname = 'Netscape';
            $ub = "Netscape";
        }elseif(preg_match('/Edge/i',$u_agent)){
            $bname = 'Edge';
            $ub = "Edge";
        }elseif(preg_match('/Trident/i',$u_agent)){
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        return $bname;
    }

    public function otpConfirm($request)
    {
        DB::beginTransaction();
        try{
            $temp = TempStore::find($request['id']);

            if ($temp->otp != $request['otp']):
                return false;
            endif;

            $user = new User();
            $user->first_name       = $temp->first_name;
            $user->last_name        = $temp->last_name;
            $user->email            = $temp->email;
            $user->phone_number     = $temp->phone_number;
            $user->password         = $temp->password;
            $user->permissions      = [];
            $user->user_type        = 'merchant';
            $user->is_primary       = 1;
            $user->hub_id           = 1;

            $user->save();

            $this->registerMerchant($temp, $user->id);

            $activation = Activation::create($user);
            Activation::complete($user, $activation->code);

            if (Reminder::exists($user)) :
                $remainder = Reminder::where('user_id', $user->id)->first();
            else :
                $remainder = Reminder::create($user);
            endif;

            Log::info($remainder->code);
            //send a mail to user
            try {
                sendMail($user, $remainder->code, 'verify_email', '');
            } catch (\Exception $e){
                \Log::info($e->getMessage());
            }

            $temp->delete();

            DB::commit();
            return $user;

        } catch (\Exception $e) {
	    \Log::info($e->getMessage());
            DB::rollback();
            return false;
        }
    }

    public function resendOtp($id)
    {
        DB::beginTransaction();
        try{
            $temp = TempStore::find($id);
            $temp->otp = rand(10000 , 99999);

            $sms_body = __('hello').' '. $temp->first_name .', '. __('use').' '. $temp->otp .' '. __('to_verify_your_phone_number_on_greenx_ecourier');
//            dd($temp->phone_number);

            dd($this->smsSender('resend-otp', $temp->phone_number, $sms_body, false));

            $temp->save();

            DB::commit();
            return $temp->id;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function registerMerchant($data, $user_id)
    {

        $cod_charges = CodCharge::all();
        $charges     = Charge::all();

        $array_cod_charges = [];
        foreach($cod_charges as $cod_charge){
            $array_cod_charges[$cod_charge->location] = $cod_charge->charge;
        }

        $array_charges = [];
        foreach($charges as $charge){
            $array_charges[$charge->weight] = [
                'same_day' => $charge->same_day,
                'next_day' => $charge->next_day,
                // 'frozen' => $charge->frozen,
                'sub_city' => $charge->sub_city,
                'outside_dhaka' => $charge->outside_dhaka,
                // 'third_party_booking' => $charge->third_party_booking
            ];
        }


        $merchant = new Merchant();

        $merchant->user_id          = $user_id;
        $merchant->company          = $data['company'];
        $merchant->phone_number     = $data['phone_number'];
        $merchant->address          = $data['address'];
        $merchant->vat              = 0.00;
        $merchant->api_key          = $this->generate_random_string(15);
        $merchant->secret_key       = $this->generate_random_string(30);


        $merchant->cod_charges        = $array_cod_charges;
        $merchant->charges            = $array_charges;

        $merchant->save();

        $merchant_account                    = new MerchantAccount();
        $merchant_account->details           = 'opening_balance';
        $merchant_account->source            = 'opening_balance';
        $merchant_account->date              = date('Y-m-d');
        $merchant_account->type              = 'income';
        $merchant_account->amount            = 0.00;
        $merchant_account->merchant_id       = $merchant->id;
        $merchant_account->save();

        $this->saveMerchantPaymentAccount($merchant->id);
        $this->saveMerchantShop($merchant->id, $data);
    }

    public function updateMerchantByMerchant($request)
    {
        DB::beginTransaction();
        try{
            $merchant = Merchant::find($request->merchant);
            $user               = $merchant->user;
            $user->phone_number = $request['phone_number'];
            $user->save();

            $merchant->company          = $merchant->company;
            $merchant->phone_number     = $request['phone_number'];
            $merchant->city             = $request['city'];
            $merchant->zip              = $request['zip'];
            $merchant->address          = $request['address'];
            $merchant->website          = $request['website'];
            $merchant->billing_street   = $request['billing_street'];
            $merchant->billing_city     = $request['billing_city'];
            $merchant->billing_zip      = $request['billing_zip'];

            //check if new nid file selected than replace
            if ($request->file('nid')):
                $this->removeOldFile($merchant->nid);
                $merchant->nid              = $this->fileUpload($request->file('nid'), 'nid');
            endif;
            //check if new trade license file selected than replace
            if ($request->file('trade_license')):
                $this->removeOldFile($merchant->trade_license);
                $merchant->trade_license           = $this->fileUpload($request->file('trade_license'), 'trade-license');
            endif;

            $merchant->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function saveMerchantPaymentAccount($merchant_id)
    {
        $payment_account = new MerchantPaymentAccount();

        $payment_account->merchant_id = $merchant_id;

        $payment_account->save();
    }

    public function saveMerchantShop($merchant_id , $request)
    {
        $merchant_shop = new Shop();

        $merchant_shop->merchant_id = $merchant_id;
        $merchant_shop->shop_name = $request['company'];
        $merchant_shop->contact_number = $request['phone_number'];
        $merchant_shop->shop_phone_number = $request['phone_number'];
        $merchant_shop->address = $request['address'];
        $merchant_shop->default = 1;

        $merchant_shop->save();
    }

    public function shopStore($request)
    {
        DB::beginTransaction();
        try{
            $shop = new Shop();
            $shop->merchant_id = $request->merchant;
            $shop->shop_name = $request->shop_name;
            $shop->contact_number = $request->contact_number;
            $shop->shop_phone_number = $request->shop_phone_number;
            $shop->address = $request->address;
            $shop->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function shopUpdate($request)
    {

        DB::beginTransaction();
        try{
            $shop = Shop::find($request->shop);
            if(hasPermission('merchant_shop_name_update')){
                $this->nameChangeRequest("shop", $shop->shop_name , $request['shop_name'],  $shop->merchant_id, $shop->id, "accept");
                $shop->shop_name = $request['shop_name'];
            }else{
                $shop->shop_name = $shop->shop_name;
            }

            $shop->contact_number = $request->contact_number;
            $shop->shop_phone_number = $request->shop_phone_number;
            $shop->address = $request->address;
            $shop->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function paymentAccount($id)
    {
        try {
            $merchant = $this->get($id);

            $payment_account = $merchant->paymentAccount;

            return $payment_account;
        }catch (\Exception $e) {
            return false;
        }
    }

    public function updateBankDetails($data)
    {
        DB::beginTransaction();
        try{
            $merchant = $this->get($data['merchant']);
            $payment_account = $merchant->paymentAccount;

            $payment_account->selected_bank = $data['selected_bank'];
            $payment_account->bank_branch = $data['bank_branch'];
            $payment_account->bank_ac_name = $data['bank_ac_name'];
            $payment_account->bank_ac_number = $data['bank_ac_number'];
            $payment_account->routing_no = $data['routing_no'];
            $payment_account->save();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function updateOthersAccountDetails($data)
    {
        DB::beginTransaction();
        try{
            $merchant = $this->get($data['merchant']);
            $payment_account = $merchant->paymentAccount;

            $payment_account->bkash_number = $data['bkash_number'];
            $payment_account->bkash_ac_type = $data['bkash_ac_type'];
            $payment_account->rocket_number = $data['rocket_number'];
            $payment_account->rocket_ac_type = $data['rocket_ac_type'];
            $payment_account->nogod_number = $data['nogod_number'];
            $payment_account->nogod_ac_type = $data['nogod_ac_type'];
            $payment_account->save();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function apiCredentialsUpdate($request)
    {
        DB::beginTransaction();
        try {
            $merchant = $this->get($request->id);

            $merchant->api_key      = $request->api_key;
            $merchant->secret_key   = $request->secret_key;
            $merchant->save();

            DB::commit();
            return true;
        } catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function permissionUpdate($request, $merchant)
    {
        DB::beginTransaction();
        try {
            $user = $merchant->user;
            $user->permissions = isset($request->permissions) ? $request->permissions : [];
            $user->save();

            DB::commit();
            return true;
        } catch (\Exception $e){
            DB::Rollback();
            return false;
        }
    }

    public function nameChangeRequest($type,  $old_name, $request_name,  $merchant_id=null, $shop_id=null, $status=null)
    {

        try{
            DB::beginTransaction();
            $name_change  = new NameChangeRequest;
            $name_change->merchant_id =  $merchant_id;
            $name_change->shop_id     =  $shop_id;
            $name_change->request_id =  \Sentinel::getUser()->id;
            $name_change->type =  $type;
            $name_change->request_name =  $request_name;
            $name_change->old_name =  $old_name;
            $name_change->created_at =  date('Y-m-d h:i:s');
            $name_change->updated_at =  null;
            $name_change->status =  "pending";
            if($status == "accept"){
                $name_change->status =  "accept";
                $name_change->request_id =  '';
                $name_change->created_at =  null;
                $name_change->updated_at =  date('Y-m-d h:i:s');
                $name_change->process_id =  \Sentinel::getUser()->id;
            }
            $name_change->save();

            DB::commit();
            return true;

        }catch(Exception $e){
            DB::Rollback();
            return false;
        }

    }

    public function chengeRequest()
    {
        $changes = NameChangeRequest::orderBy('id', 'desc')->paginate(\Config::get('greenx.parcel_merchant_paginate'));
        return $changes;

    }

    public function chengeRequestAuthorize($id)
    {
        try{
            DB::beginTransaction();

            $name_change = NameChangeRequest::find($id);

            //company change
            if($name_change->type =="company"){
                $merchant = Merchant::find($name_change->merchant_id);
                $merchant->company = $name_change->request_name;
                $merchant->save();
                $this->SendSmsToMerchant("company_name_change_event", $merchant->phone_number, $name_change->request_name);
            }elseif($name_change->type =="shop"){
                $shop = Shop::find($name_change->shop_id);
                $shop->shop_name  = $name_change->request_name;
                $shop->save();
                $this->SendSmsToMerchant("shop_name_change_event", $shop->shop_phone_number, $name_change->request_name);
            }else{
                return false;
            }
            $name_change->updated_at =  date('Y-m-d h:i:s');
            $name_change->process_id =  \Sentinel::getUser()->id;
            $name_change->status =  "accept";
            $name_change->save();



            DB::commit();
            return true;
        }catch(Exception  $e){
            DB::Rollback();
            return false;
        }

    }

    public function chengeRequestDelete($id)
    {
        try{
            DB::beginTransaction();
            $name_change = NameChangeRequest::find($id);
            $name_change->updated_at =  date('Y-m-d h:i:s');
            $name_change->process_id =  \Sentinel::getUser()->id;
            $name_change->status =  "decline";
            $name_change->save();
            DB::commit();
            return true;

        }catch(Exception $e){
            DB::Rollback();
            return false;
        }
    }

    public function merchantNameRequest($request)
    {
         $merchant = Merchant::find($request->merchant);
         if($request->request_name !==  $merchant->company){
            if($this->nameChangeRequest("company", $merchant->company , $request->request_name,  $merchant->id, $shop_id=null)){
                return true;
             }else{
                return false;
             }
         }else{
            return false;
         }

    }

    public function merchantShopNameRequest($request)
    {
        $shop = Shop::find($request->shop);
        if($request->request_name !== $shop->shop_name){
            if($this->nameChangeRequest("shop", $shop->shop_name , $request->request_name,  $shop->merchant_id, $shop_id=$shop->id)){
                return true;
             }else{
                return false;
             }
        }else{
            return false;
        }

    }

    public function SendSmsToMerchant($title, $phone_number, $name)
    {
        $sms_template = NameSmsTemplate::where('subject', $title)->first();
        if($sms_template->sms_to_merchant):
            $sms_body = str_replace('{company_name}', $name, $sms_template->content);
            $sms_body = str_replace('{shop_name}', $name, $sms_body);
            $this->smsSender('company_name_change_event', $phone_number, $sms_body, $sms_template->masking);
        endif;
    }

    public function nameChangeFilter($request)
    {
        $merchant = $request->merchant;
        $type = $request->type;
        $status = $request->status;
        $query = NameChangeRequest::query();

        if ($request->type != "all") {
            $query->where('type', $type);
        }

        if ($request->status != "all") {
            $query->where('status', $status);
        }
        if($request->merchant !=''){
            $query->where('merchant_id', $merchant);
        }

        return $query->paginate(\Config::get('greenx.parcel_merchant_paginate'));

    }

    public function merchantLog($id)
    {
       return $edit_logs = MerchantEditLog::where('merchant_id', $id)->orderBy('id', 'desc')->paginate(\Config::get('greenx.parcel_merchant_paginate'));
       //return $edit_logs = MerchantEditLog::where('merchant_id', $id)->orderBy('id', 'desc')->get();

    }

}
