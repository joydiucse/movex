<?php

namespace App\Http\Controllers\Api\V10;

use App\Http\Controllers\Controller;
use App\Models\LogActivity;
use App\Traits\MerchantApiTrait;
use App\Traits\MerchantBalanceTrait;
use Illuminate\Http\Request;

class MerchantAuthController extends Controller
{
    use MerchantBalanceTrait;
    use MerchantApiTrait;

    public function balance(Request $request)
    {
        try{
            $data = $this->getMerchant($request);
            if ($data['code'] == 401):
                return response()->json($data['response'], $data['code']);
            else:
                $merchant = $data['merchant'];
            endif;

            $balance  = number_format($this->merchantBalance($merchant->id)['current_payable'],2);

            $response = [
                'status' => 200,
                'current_balance' => $balance,
            ];
            $code = 200;

            return response()->json($response, $code);

        } catch (\Exception $e){
            $response = [
                'status' => 500,
                'message' => __('something_went_wrong_please_try_again'),
            ];
            return response()->json($response, 500);
        }
    }

    public function profile(Request $request)
    {
        try{
            $data = $this->getMerchant($request);
            if ($data['code'] == 401):
                return response()->json($data['response'], $data['code']);
            else:
                $merchant = $data['merchant'];
            endif;

            $data['personal_details'] = [
                'first_name'  => $merchant->user->first_name,
                'last_name'   => $merchant->user->last_name,
                'email'       => $merchant->user->email,
                'last_login'  => $merchant->user->last_login != ""? $merchant->user->last_login:'',
            ];

            $data['company_information'] = [
                'company_name'  => $merchant->company,
                'phone_number'  => $merchant->phone_number,
                'city'          => $merchant->city,
                'zip'           => $merchant->zip,
                'address'       => $merchant->address,
            ];

            $data['log_activities'] = [
                LogActivity::where('user_id', $merchant->user_id)->orderBy('id', 'desc')->limit(20)->get(['ip','browser','platform','created_at']),
            ];

            unset($data['merchant']);

            $response = [
                'status' => 200,
                'profile' => $data,
            ];
            $code = 200;

            return response()->json($response, $code);

        } catch (\Exception $e){
            $response = [
            'status' => 500,
            'message' => __('something_went_wrong_please_try_again'),
            ];
            return response()->json($response, 500);
        }

    }
}
