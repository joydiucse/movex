<?php

namespace App\Http\Controllers\Api\V10;

use Illuminate\Http\Request;
use App\Traits\SmsSenderTrait;
use App\Traits\MerchantApiTrait;
use Illuminate\Support\Facades\DB;
use App\Models\WithdrawSmsTemplate;
use App\Http\Controllers\Controller;
use App\Traits\MerchantBalanceTrait;
use App\Models\Account\CompanyAccount;
use App\Models\Account\MerchantAccount;
use App\Models\Account\MerchantWithdraw;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    use MerchantApiTrait;
    use MerchantBalanceTrait;
    use SmsSenderTrait;

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'payment_to' => 'required|in:bank,bkash,nogod,rocket',
                'bank_name'      => 'required_if:payment_to,bank',
                'bank_branch'    => 'required_if:payment_to,bank',
                'bank_ac_number' => 'required_if:payment_to,bank',
                'bank_ac_holder' => 'required_if:payment_to,bank',
                'ac_type'        => 'required_if:payment_to,bkash,nogod,rocket|in:merchant,personal',
                'phone_number'   => 'required_if:payment_to,bkash,nogod,rocket|numeric'
            ]);

            $code = 200;

            if ($validator->fails()) :
                $response = [
                    'status' => 422,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 422);
            endif;

            $data = $this->getMerchant($request);
            if ($data['code'] == 401):
                return response()->json($data['response'], $data['code']);
            else:
                $merchant = $data['merchant'];
            endif;

            $merchant_details = $this->merchantBalance($merchant->id);

            $amount             = $merchant_details['current_payable'];

            if ($amount <= 50):
                $response = [
                    'status'    => 422,
                    'message'   => __('you_do_not_have_enough_amount_to_make_a_payment_request'),
                ];
                return response()->json($response, 422);
            endif;

            $parcels            = $merchant_details['parcels'];
            $merchant_accounts  = $merchant_details['merchant_accounts'];

            if($request->payment_to == 'bank'):
                $request['pay_by'] = $request->bank_name .', '.$request->bank_branch .', '.$request->bank_ac_holder.', '.$request->bank_ac_number;
                if ($request->routing_no != ''):
                    $request['pay_by'].= ', '.__('routing_no').': '.$request->routing_no;
                endif;
                $data['account_details']    = [$request->bank_name,$request->bank_branch,$request->bank_ac_holder,$request->bank_ac_number,$request->routing_no];
            elseif($request->payment_to == 'bKash' || $request->payment_to == 'rocket' || $request->payment_to == 'nogod'):
                $request['pay_by'] = __($request->payment_to).', '.$request->phone_number.', '.__($request->ac_type);
                $data['account_details']    = [$request->payment_to,$request->phone_number,__($request->ac_type)];
            endif;

            //withdraw table data insertion
            $withdraw                      = new MerchantWithdraw();
            $withdraw->withdraw_id         = 'GNX'.rand(10000000,99999999);
            $withdraw->merchant_id         = $merchant->id;
            $withdraw->amount              = (double)$amount;
            $withdraw->status              = 'pending';
            $withdraw->withdraw_to         = $request->payment_to;
            $withdraw->account_details     = json_encode(array_filter($data['account_details']));
            $withdraw->created_by          = $merchant->user->id;
            $withdraw->note                = $request->note;
            $withdraw->date                = date('Y-m-d');
            $withdraw->save();

            if (isset($parcels)):
                foreach ($parcels as $parcel):
                    $parcel->withdraw_id = $withdraw->id;
                    $parcel->save();
                endforeach;
            endif;

            if (isset($merchant_accounts)):
                foreach ($merchant_accounts as $merchant_account):
                    $merchant_account->payment_withdraw_id = $withdraw->id;
                    $merchant_account->save();
                endforeach;
            endif;

            //company table data insertion and calculation
            $company_account                       = new CompanyAccount();
            $company_account->source               = 'payment_withdraw_by_merchant';
            $company_account->details              = $request->note ?? __('payment_withdraw_by_merchant');
            $company_account->date                 = date('Y-m-d');
            $company_account->type                 = 'expense';
            $company_account->amount               = $withdraw->amount;
            $company_account->created_by           = $merchant->user->id;
            $company_account->merchant_id          = $withdraw->merchant_id;
            $company_account->merchant_withdraw_id = $withdraw->id;
            $company_account->save();

            //merchant account transaction calculation and insertion

            $merchant_account                       = new MerchantAccount();
            $merchant_account->source               = 'payment_withdraw_by_merchant';
            $merchant_account->merchant_withdraw_id = $withdraw->id;
            $merchant_account->details              = $request->note ?? __('payment_withdraw_by_merchant');
            $merchant_account->date                 = date('Y-m-d');
            $merchant_account->type                 = 'expense';
            $merchant_account->amount               = $withdraw->amount;
            $merchant_account->merchant_id          = $withdraw->merchant_id;
            $merchant_account->company_account_id   = $company_account->id;
            $merchant_account->save();

            // merchant sms start
            $sms_template = WithdrawSmsTemplate::where('subject','payment_create_event')->first();

            $sms_body = str_replace('{account_details}', $withdraw->account_details, $sms_template->content);
            $sms_body = str_replace('{amount}', $withdraw->amount, $sms_body);
            $sms_body = str_replace('{payment_id}', $withdraw->withdraw_id, $sms_body);
            $sms_body = str_replace('{our_company_name}', __('app_name'), $sms_body);
            $sms_body = str_replace('{current_date_time}', date('M d, Y h:i a'), $sms_body);

            if($sms_template->sms_to_merchant):
                $this->smsSender('payment_create_event', $withdraw->merchant->phone_number, $sms_body, $sms_template->masking);
            endif;
            //merchant sms end

            DB::commit();

            $response = [
                'status'    => 201,
                'message'   => __('payment_request_created_successfully'),
                'request_details'  => $withdraw->makeHidden(['merchant_id','id','note']),
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

    public function paymentList(Request $request)
    {
        try {
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
                'message' => 'payment_lists',
                'payments'    => array_values($merchant->withdraws->makeHidden(['merchant_id','id'])->skip($offset)->take($limit)->toArray()),
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

    public function paymentLogs(Request $request)
    {
        try {
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
                'message' => 'payment_logs',
                'logs'    => array_values($merchant->accountStatements
                    ->makeHidden(['id','merchant_withdraw_id','payment_withdraw_id','parcel_withdraw_id','is_paid','balance','merchant_id','parcel_id','company_account_id'])
                    ->skip($offset)->take($limit)->toArray()),
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
