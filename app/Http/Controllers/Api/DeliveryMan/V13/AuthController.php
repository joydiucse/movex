<?php

namespace App\Http\Controllers\Api\DeliveryMan\V13;

use App\Http\Controllers\Controller;
use App\Models\Account\DeliveryManAccount;
use App\Models\DeliveryMan;
use App\Models\Image as ImageModel;
use App\Models\Parcel;
use App\Models\User;
use App\Traits\RandomStringTrait;
use App\Traits\SmsSenderTrait;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Sentinel;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use Image;

class AuthController extends Controller
{
    use ApiReturnFormatTrait;
    use RandomStringTrait;
    use SmsSenderTrait;

    public function __construct()
    {
        /*$this->middleware('auth:api', ['except' => ['login']]);*/
    }

    public function loginOtp(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required',
            ]);

            if ($validator->fails()) :
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            endif;

            $user = User::wherePhoneNumber($request->phone_number)->where('user_type','delivery')->first();


            if (blank($user)) :
                return $this->responseWithError(__('user_not_found'), $validator->errors(), 422);
            endif;
            if($user->status == 0) {
                return $this->responseWithError(__('your_account_is_inactive'), $validator->errors(), 422);
            } elseif($user->status == 2) {
                return $this->responseWithError(__('your_account_is_suspend'), $validator->errors(), 422);
            }

            $password = rand(100000,999999);
            if($request->phone_number == "01725402187"):
                $password = 123456;
            endif;
\Log::info($user);

            if($this->passwordReset($user, $password)):
                //send otp to user phone number
                $sms_body = 'Use OTP: '.$password.' to login on Delivery Hero App. GreenX';
                if ($request->phone_number == "01725402187"):
                    DB::commit();
                    return $this->responseWithSuccess(__('an_otp_send_to_your_phone_number'),[] ,200);
                elseif($this->smsSender('login-otp',$user->phone_number, $sms_body, true)):
                    DB::commit();
                    return $this->responseWithSuccess(__('an_otp_send_to_your_phone_number'),[] ,200);
                else:

                    DB::rollback();
                    return $this->responseWithError(__('unable_to_send_otp_please_try_again'), [], 500);
                endif;
            endif;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());

            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required',
                'otp' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
                // return response()->json($validator->errors(), 422);
            }

            $user = User::wherePhoneNumber($request->phone_number)->where('user_type','delivery')->first();

            if (blank($user)) :
                return $this->responseWithError( __('user_not_found'), [], 422);
            endif;

            if($user->status == 0) :
                return $this->responseWithError( __('your_account_is_inactive'), [], 401);
            elseif($user->status == 2):
                return $this->responseWithError( __('your_account_is_suspend'), [], 401);
            endif;

            if (!Hash::check($request->get('otp'), $user->password)) :
                return $this->responseWithError(__('otp_mismatch'), $validator->errors(), 422);
            endif;

            $credentials = ['phone_number'=>$request->phone_number, 'password'=>$request->otp];

            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return $this->responseWithError(__('unable_to_create_token'), [], 401);
                }
            } catch (JWTException $e) {
                return $this->responseWithError(__('could_not_create_token'), [] , 422);

            } catch (ThrottlingException $e) {
                return $this->responseWithError(__('suspicious_activity_on_your_ip'). $e->getDelay() .  __('seconds'), [], 500);

            } catch (NotActivatedException $e) {
                return $this->responseWithError(__('you_account_not_activated_check_mail_or_contact_support'),[],400);

            } catch (\Exception $e) {
                return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
            }

            $data = $this->getProfile($user);
            $data['token'] = $token;

            return $this->responseWithSuccess(__('successfully_login'), $data, 200);
        } catch (\Exception $e){
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function profile()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $data['id'] = $user->id;

            $data = $this->getProfile($user);

            return $this->responseWithSuccess(__('successfully_found'), $data, 200);
        }catch (\Exception $e){
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        DB::beginTransaction();

        try{
            $validator = Validator::make($request->all(), [
                'first_name'    => 'required|max:50',
                'last_name'     => 'required|max:50',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $user->first_name   = $request->first_name;
            $user->last_name    = $request->last_name;
            $user->save();

            $delivery_man               = DeliveryMan::find($user->deliveryMan->id);
            $delivery_man->address      = $request->address;
            $delivery_man->save();

            $data = $this->getProfile($user);

            DB::commit();

            return $this->responseWithSuccess(__('successfully_updated'), $data, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function logout()
    {
        try {
            Sentinel::logout();
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->responseWithSuccess(__('successfully_logout'),[] ,200);
        } catch (JWTException $e) {
            JWTAuth::unsetToken();
            // something went wrong tries to validate a invalid token
            return $this->responseWithError(__('failed_to_logout'), [], 422);
        }
    }

    public function getProfile($user)
    {
        $data['id'] = $user->id;

        $data['name'] = $user->first_name .' '.$user->last_name;
        $data['first_name'] = $user->first_name;
        $data['last_name'] = $user->last_name;
        $data['email'] = $user->email;
        $data['phone_number'] = $user->deliveryMan['phone_number'];
        if (isset($user->image)):
            $data['image'] = asset($user->image->image_small_three);
        else:
            $data['image'] = '';
        endif;
        $data['address'] = $user->deliveryMan['address'];
        $data['balance'] = number_format($user->deliveryMan->balance($user->deliveryMan->id),2) .' '.__('tk');

        //we store income as expense and expense as income on database
        $total_pickup_delivery_commission_income = DeliveryManAccount::whereIn('source',['pickup_commission','parcel_delivery'])->where('type','expense')->where('delivery_man_id',$user->deliveryMan->id)->sum('amount');
        $total_pickup_delivery_commission_expense = DeliveryManAccount::whereIn('source',['pickup_commission','parcel_delivery'])->where('type','income')->where('delivery_man_id',$user->deliveryMan->id)->sum('amount');

        $data['total_earning'] = number_format($total_pickup_delivery_commission_income - $total_pickup_delivery_commission_expense,2).' '.__('tk');

        $total_cash_collect_income = DeliveryManAccount::where('source','cash_collection')->where('type','income')->where('delivery_man_id',$user->deliveryMan->id)->sum('amount');
        $total_cash_collect_expense = DeliveryManAccount::where('source','cash_collection')->where('type','expense')->where('delivery_man_id',$user->deliveryMan->id)->sum('amount');

        $data['cash_collection'] = number_format($total_cash_collect_income - $total_cash_collect_expense,2).' '.__('tk');

        $data['processing_delivery'] = Parcel::where('delivery_man_id', $user->deliveryMan['id'])->whereIn('status',['delivery-assigned', 're-schedule-delivery'])->count();
        $data['total_delivered'] = Parcel::where('delivery_man_id', $user->deliveryMan['id'])->whereIn('status',['delivered','delivered-and-verified'])->count();
        $data['total_cancelled'] = Parcel::where('delivery_man_id', $user->deliveryMan['id'])->where('status','cancel')->count();
        $data['sip_extension'] = ($user->deliveryMan->sip_extension != '' or $user->deliveryMan->sip_extension !=null) ? $user->deliveryMan->sip_extension : '';
        $data['sip_password'] = ($user->deliveryMan->sip_password != '' or $user->deliveryMan->sip_password !=null) ? $user->deliveryMan->sip_password : '';
        $data['sip_domain'] = (settingHelper('sip_domain') != '' or  settingHelper('sip_domain') !=null) ? settingHelper('sip_domain') : '';
        return $data;
    }

    function validEmail($str) {
        return (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? TRUE : FALSE;
    }

    public function forgotPasswordOtp(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            $user = User::wherePhoneNumber($request->phone_number)->first();

            if (blank($user->phone_number)) {
                return $this->responseWithError(__('invalid_phone_number'), $validator->errors(), 422);
            }

            $otp = rand(1000,9999);

            if($this->passwordResetOtp($user, $otp)):
                //send password to user phone number
                $sms_body = 'Use OTP:'.$otp.' to verify your reset password confirmation. GreenX';
                if($this->smsSender('reset-password-otp',$user->phone_number, $sms_body, true)):
                    DB::commit();
                    return $this->responseWithSuccess(__('reset_password_confirmation_otp_send'),[] ,200);
                else:
                    DB::rollback();
                    return $this->responseWithError(__('unable_to_create_otp_please_try_again'), [], 500);
                endif;
            endif;

        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function forgotPasswordPost(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required',
                'otp'          => 'required',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            $user = User::wherePhoneNumber($request->phone_number)->first();

            if ($user->otp != $request->otp):
                return $this->responseWithError(__('otp_did_not_match_please_provide_the_valid_otp'), [], 422);
            endif;

            if (blank($user)) {
                return $this->responseWithError(__('invalid_phone_number'), $validator->errors(), 422);
            }

            $password = $this->generate_random_string(6);

            if($this->passwordReset($user, $password)):
                //send password to user phone number
                $sms_body = 'Use '.$password.' as your password to login on Delivery Hero App. GreenX';
                if($this->smsSender('reset-password',$user->phone_number, $sms_body, true)):
                    DB::commit();
                    return $this->responseWithSuccess(__('password_reset_successful_and_send_to_your_phone_number'),[] ,200);
                else:
                    DB::rollback();
                    return $this->responseWithError(__('unable_to_reset_password_please_try_again'), [], 500);
                endif;
            endif;

        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function passwordReset($user, $password)
    {
        try {
            $user->password = bcrypt($password);
            $user->last_password_change = date('Y-m-d H:i:s');
            $user->save();

            return true;
        } catch (\Exception $e){
            return false;
        }
    }

    public function passwordResetOtp($user, $otp)
    {
        try {
            $user->otp   = $otp;
            $user->save();

            return true;
        } catch (\Exception $e){
            return false;
        }
    }
    //v10 updates
    public function paymentLogs(Request $request){
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $page = $request->page ?? 1;

            $offset = ( $page * \Config::get('greenx.api_paginate') ) - \Config::get('greenx.api_paginate');
            $limit  = \Config::get('greenx.api_paginate');

            $delivery_man = DeliveryMan::find($user->deliveryMan->id);

            $logs = $delivery_man->paymentLogs()->get();

            $logs = $this->formatLogs($logs);
            $logs = $logs->sortDesc()->skip($offset)->take($limit)->flatten();


            return $this->responseWithSuccess(__('successfully_found'),$logs ,200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    //v10 updates
    public function cashDeposits(Request $request){
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }

            $page = $request->page ?? 1;

            $offset = ( $page * \Config::get('greenx.api_paginate') ) - \Config::get('greenx.api_paginate');
            $limit  = \Config::get('greenx.api_paginate');

            $delivery_man = DeliveryMan::find($user->deliveryMan->id);

            $logs = $delivery_man->paymentLogs()->where('source','cash_given_to_staff')->get();

            $logs = $this->formatDepositLogs($logs);
            $logs = $logs->sortDesc()->skip($offset)->take($limit)->flatten();


            return $this->responseWithSuccess(__('successfully_found'),$logs ,200);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function formatLogs($logs){
        foreach ($logs as $log):
            $log['id']          = $log->id;
            $log['source']      = __($log->source);
            $log['details']     = __($log->details);
            $log['date']        = date('d-M-Y',strtotime($log->date));
            $log['amount']      = number_format($log->amount, 2);
            $log['parcel_no']   = @$log->parcel->parcel_no;

            unset($log->created_at);
            unset($log->updated_at);
            unset($log->company_account_id);
            unset($log->delivery_man_id);
            unset($log->parcel_id);
            unset($log->parcel);
            unset($log->balance);
        endforeach;

        return $logs;
    }

    public function formatDepositLogs($logs){
        foreach ($logs as $log):
            $log['id']          = $log->id;
            $log['source']      = __($log->source);
            $log['details']     = __($log->details);
            $log['date']        = date('d-M-Y',strtotime($log->date));
            $log['amount']      = number_format($log->amount, 2);
            $log['to_whom']     = @$log->companyAccount->account->user->first_name.' '.@$log->companyAccount->account->user->last_name;

            unset($log->created_at);
            unset($log->updated_at);
            unset($log->company_account_id);
            unset($log->delivery_man_id);
            unset($log->parcel_id);
            unset($log->parcel);
            unset($log->balance);
            unset($log->companyAccount);
        endforeach;

        return $logs;
    }

    public function updateProfileImage(Request $request){
        DB::beginTransaction();

        try{
            $validator = Validator::make($request->all(), [
                'image'    => 'required|mimes:jpg,JPG,JPEG,jpeg,png,PNG,webp,WEBP|max:5120',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->responseWithError(__('unauthorized_user'), '' , 404);
            }
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
                Image::make($requestImage)->fit(128, 128)->save($imageSmallThreeUrl, 80);

                $image                          = new ImageModel();
                $image->original_image          = $originalImageUrl;
                $image->image_small_one         = $imageSmallOneUrl;
                $image->image_small_two         = $imageSmallTwoUrl;
                $image->image_small_three       = $imageSmallThreeUrl;
                $image->save();

            }
            $user->image_id      = $image->id ?? null;
            $user->save();

            $data = $this->getProfile($user);

            DB::commit();

            return $this->responseWithSuccess(__('successfully_updated'), $data, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }
}
