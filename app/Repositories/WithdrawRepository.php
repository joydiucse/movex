<?php

namespace App\Repositories;

use App\Models\Account\MerchantWithdraw;
use App\Models\Parcel;
use App\Models\StaffAccount;
use App\Models\WithdrawSmsTemplate;
use App\Repositories\Interfaces\Merchant\MerchantInterface;
use App\Repositories\Interfaces\WithdrawInterface;
use App\Traits\CommonHelperTrait;
use App\Traits\SmsSenderTrait;
use Sentinel;
use DB;
use Image;
use App\Models\Account\CompanyAccount;
use App\Models\Account\MerchantAccount;

class WithdrawRepository implements WithdrawInterface {
    use CommonHelperTrait;
    use SmsSenderTrait;

    protected $merchants;

    public function __construct(MerchantInterface $merchants){
        $this->merchants       = $merchants;
    }

    public function all()
    {
        return MerchantWithdraw::all();
    }

    public function paginate($limit)
    {
        return MerchantWithdraw::orderBy('id', 'desc')->paginate($limit);
    }

    public function get($id)
    {
        return MerchantWithdraw::find($id);
    }

    public function get_account_details($merchant, $withdraw_to)
    {
        $payment_accounts = $this->merchants->get($merchant)->paymentAccount;

        if($withdraw_to == 'bank'):
            $data['pay_by']             = __($payment_accounts->selected_bank) .', '.$payment_accounts->bank_branch .', '.$payment_accounts->bank_ac_name.', '.$payment_accounts->bank_ac_number;
            $data['account_details']    = [__($payment_accounts->selected_bank),$payment_accounts->bank_branch,$payment_accounts->bank_ac_name,$payment_accounts->bank_ac_number,$payment_accounts->routing_no];
        elseif($withdraw_to == 'bKash'):
            $data['pay_by']             = __('bKash').', '.$payment_accounts->bkash_number.', '.__($payment_accounts->bkash_ac_type);
            $data['account_details']    = [__('bKash'),$payment_accounts->bkash_number,__($payment_accounts->bkash_ac_type)];
        elseif($withdraw_to == 'rocket'):
            $data['pay_by']             = __('rocket').', '.$payment_accounts->rocket_number.', '.__($payment_accounts->bkash_ac_type);
            $data['account_details']    = [__('rocket'),$payment_accounts->rocket_number,__($payment_accounts->bkash_ac_type)];
        elseif($withdraw_to == 'nogod'):
            $data['pay_by']             = __('nogod').', '.$payment_accounts->nogod_number.', '.__($payment_accounts->bkash_ac_type);
            $data['account_details']    = [__('nogod'),$payment_accounts->nogod_number,__($payment_accounts->bkash_ac_type)];
        endif;

        return $data;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try{
            //withdraw table data insertion with checking whether transaction processed or not
            $withdraw                      = new MerchantWithdraw();
            $withdraw->withdraw_id         = 'MVX'.rand(10000000,99999999);
            $withdraw->merchant_id         = $request->merchant;
            $withdraw->amount              = (double)$request->amount;
            if(isset($request->status)):
                $withdraw->status          = $request->status;
            endif;
            $withdraw->withdraw_to         = $request->withdraw_to;

            $account_pay_by                = $this->get_account_details($request->merchant, $request->withdraw_to);
            $request['pay_by']             = $account_pay_by['pay_by'];
            $account_details               = json_encode(array_filter($account_pay_by['account_details']));

            $withdraw->account_details     = $account_details;
            $withdraw->created_by          = Sentinel::getUser()->id;
            $withdraw->note                = $request->details;
            $withdraw->date                = date('Y-m-d');
            $withdraw->save();

            if (isset($request->parcels)):
                $parcels = Parcel::whereIn('id', $request->parcels)->get();
                foreach ($parcels as $parcel):
                    $parcel->withdraw_id = $withdraw->id;
                    $parcel->is_paid = $request->status == 'processed' ? true : false;
                    $parcel->save();
                endforeach;
            endif;

            if (isset($request->merchant_accounts)):
                $merchant_accounts = MerchantAccount::whereIn('id', $request->merchant_accounts)->get();
                foreach ($merchant_accounts as $merchant_account):
                    $merchant_account->payment_withdraw_id = $withdraw->id;
                    $merchant_account->save();
                endforeach;
            endif;

            //company table data insertion and calculation
            $company_account                       = new CompanyAccount();
            $company_account->source               = 'payment_withdraw_by_merchant';
            $company_account->details              = $request->details ?? __('payment_withdraw_by_merchant');
            $company_account->date                 = date('Y-m-d');
            $company_account->type                 = 'expense';
            $company_account->amount               = $withdraw->amount;
            $company_account->created_by           = \Sentinel::getUser()->id;
            $company_account->merchant_id          = $withdraw->merchant_id;
            $company_account->merchant_withdraw_id = $withdraw->id;

            if($request->status == 'processed'):
                $company_account->transaction_id       = $request->transaction_id ? $request->transaction_id : '';
                if($request->account != ''):
                    $company_account->account_id       = $request->account;
                    $company_account->user_id          = $company_account->account->user->id;
                endif;
                $company_account->receipt              = $request->file('receipt') ? $this->fileUpload($request->file('receipt')) : '';
            endif;

            $company_account->save();

            if($request->account != '' && $request->status == 'processed'):
                //staff account calculation and insertion if processed
                $staff_account                      = new StaffAccount();
                $staff_account->source              = 'withdraw';
                $staff_account->details             = 'payment_withdraw_by_merchant';
                $staff_account->date                = date('Y-m-d');
                $staff_account->type                = 'expense';
                $staff_account->amount              = $withdraw->amount;
                $staff_account->user_id             = $company_account->user_id;
                $staff_account->account_id          = $request->account;
                $staff_account->company_account_id  = $company_account->id;
                $staff_account->save();
            endif;

            //merchant account insertion

            $merchant_account                       = new MerchantAccount();
            $merchant_account->source               = 'payment_withdraw_by_merchant';
            $merchant_account->merchant_withdraw_id = $withdraw->id;
            $merchant_account->details              = $request->details ?? __('payment_withdraw_by_merchant');
            $merchant_account->date                 = date('Y-m-d');
            $merchant_account->type                 = 'expense';
            $merchant_account->amount               = $withdraw->amount;
            $merchant_account->merchant_id          = $withdraw->merchant_id;
            $merchant_account->company_account_id   = $company_account->id;
            $merchant_account->save();

            // merchant sms start
            $sms_template = WithdrawSmsTemplate::where('subject','payment_create_event')->first();

            $sms_body = str_replace('{account_details}', $request['pay_by'], $sms_template->content);
            $sms_body = str_replace('{amount}', $withdraw->amount, $sms_body);
            $sms_body = str_replace('{payment_id}', $withdraw->withdraw_id, $sms_body);
            if($company_account->receipt != '' || $company_account->transaction_id != ''):
                $sms_body = str_replace('{our_company_name}', 'Transaction successfully processed.'.($company_account->receipt != '' ? 'Receipt: '.asset($company_account->receipt) :'') . ($company_account->transaction_id != '' ? ', Transaction ID: '.$company_account->transaction_id : ''). ' '.__('app_name') , $sms_body);
            else:
                $sms_body = str_replace('{our_company_name}', __('app_name'), $sms_body);
            endif;
            $sms_body = str_replace('{current_date_time}', date('M d, Y h:i a'), $sms_body);

            if($sms_template->sms_to_merchant):
                $this->smsSender('payment_create_event', $withdraw->merchant->phone_number, $sms_body, $sms_template->masking);
            endif;
            //merchant sms end

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function update($request)
    {
        DB::beginTransaction();

        try{
            $withdraw                      = $this->get($request->id);
            if(isset($request->status)){
                $withdraw->status          = $request->status;
            }
            //sms purpose
            $account_pay_by             = $this->get_account_details($withdraw->merchant_id, $request->withdraw_to);
            $request['pay_by']          = $account_pay_by['pay_by'];
            $account_details            = json_encode(array_filter($account_pay_by['account_details']));

            $previous_account_details      = implode(',', json_decode($withdraw->account_details));
            $withdraw->account_details     = $account_details;
            $withdraw->withdraw_to         = $request->withdraw_to;
            $withdraw->note                = $request->details;
            $withdraw->save();

            foreach ($withdraw->parcels as $parcel):
                $parcel->is_paid = $request->status == 'processed' ? true : false;
                $parcel->save();
            endforeach;

            //company table data insertion and calculation
            $company_account                       = CompanyAccount::where('merchant_withdraw_id', $withdraw->id)->orderByDesc('id')->first();
            $company_account->details              = $request->details ?? __('payment_withdraw_by_merchant');
            $company_account->amount               = $withdraw->amount;

            if($request->status == 'processed'):
                $company_account->transaction_id       = $request->transaction_id ? $request->transaction_id : '';
                if($request->account != ''):
                    $company_account->account_id       = $request->account;
                    $company_account->user_id          = $company_account->account->user->id;
                endif;
                $company_account->receipt              = $request->file('receipt') ? $this->fileUpload($request->file('receipt')) : '';
            endif;

            $company_account->save();
            //if processed insert record to staff account
            if($request->account != '' && $request->status == 'processed'):
                //staff account calculation and insertion if processed
                $staff_account                      = new StaffAccount();
                $staff_account->source              = 'withdraw';
                $staff_account->details             = 'payment_withdraw_by_merchant';
                $staff_account->date                = date('Y-m-d');
                $staff_account->type                = 'expense';
                $staff_account->amount              = $withdraw->amount;
                $staff_account->user_id             = $company_account->user_id;
                $staff_account->account_id          = $request->account;
                $staff_account->company_account_id  = $company_account->id;
                $staff_account->save();
            endif;

            $merchant_account                       = MerchantAccount::where('merchant_withdraw_id', $withdraw->id)->orderByDesc('id')->first();
            $merchant_account->details              = $request->details ?? __('payment_withdraw_by_merchant');
            $merchant_account->date                 = date('Y-m-d');
            $merchant_account->amount               = $withdraw->amount;
            $merchant_account->save();

            // merchant sms start
            $sms_template = WithdrawSmsTemplate::where('subject','payment_update_event')->first();

            $sms_body = str_replace('{account_details}', $previous_account_details, $sms_template->content);
            $sms_body = str_replace('{new_account_details}', $request['pay_by'], $sms_body);
            $sms_body = str_replace('{amount}', $withdraw->amount, $sms_body);
            $sms_body = str_replace('{payment_id}', $withdraw->withdraw_id, $sms_body);
            if($company_account->receipt != '' || $company_account->transaction_id != ''):
                $sms_body = str_replace('{our_company_name}', 'Transaction successfully processed.'.($company_account->receipt != '' ? 'Receipt: '.asset($company_account->receipt) :'') . ($company_account->transaction_id != '' ? ', Transaction ID: '.$company_account->transaction_id : ''). ' '.__('app_name') , $sms_body);
            else:
                $sms_body = str_replace('{our_company_name}', __('app_name'), $sms_body);
            endif;
            $sms_body = str_replace('{current_date_time}', date('M d, Y h:i a'), $sms_body);

            if($sms_template->sms_to_merchant):
                $this->smsSender('payment_update_event', $withdraw->merchant->phone_number, $sms_body, $sms_template->masking);
            endif;
            //merchant sms end

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try{
            $withdraw = MerchantWithdraw::find($id);
            $withdraw->delete();
            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function statusChange($request)
    {
        $withdraw = MerchantWithdraw::find($request['id']);
        $withdraw->status = $request['status'];
        $result = $withdraw->save();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updateBankDetails($data)
    {
        DB::beginTransaction();
        try{
            $payment_account = Sentinel::getUser()->user_type == 'merchant' ? Sentinel::getUser()->merchant->paymentAccount : Sentinel::getUser()->staffMerchant->paymentAccount;

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

    public function updateOthersAccount($data)
    {
        DB::beginTransaction();
        try{
            $payment_account = Sentinel::getUser()->user_type == 'merchant' ? Sentinel::getUser()->merchant->paymentAccount : Sentinel::getUser()->staffMerchant->paymentAccount;

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

    public function chargeStatus($id, $status)
    {
        DB::beginTransaction();
        try{

            $merchant_withdraw         = MerchantWithdraw::find($id);
            $previous_status           = $merchant_withdraw->status;
            $merchant_withdraw->status = $status;
            $merchant_withdraw->save();

            //company table refund
            $company_account                       = new CompanyAccount();
            $company_account->source               = 'withdraw_cancelled';
            $company_account->details              = 'payment_withdraw_request_cancelled_by_merchant';
            $company_account->date                 = date('Y-m-d');
            $company_account->type                 = 'income';
            $company_account->amount               = $merchant_withdraw->amount;
            $company_account->created_by           = \Sentinel::getUser()->id;
            $company_account->merchant_id          = $merchant_withdraw->merchant_id;
            $company_account->merchant_withdraw_id = $merchant_withdraw->id;
            $company_account->save();
            //refund end for company

            $merchant_account                       = new MerchantAccount();
            $merchant_account->source               = 'withdraw_cancelled';
            $merchant_account->merchant_withdraw_id = $merchant_withdraw->id;
            $merchant_account->details              = 'withdraw_request_cancelled';
            $merchant_account->date                 = date('Y-m-d');
            $merchant_account->type                 = 'income';
            $merchant_account->amount               = $merchant_withdraw->amount;
            $merchant_account->merchant_id          = $merchant_withdraw->merchant_id;
            $merchant_account->company_account_id   = $company_account->id;
            $merchant_account->save();
            //merchant refund end

            foreach ($merchant_withdraw->parcels as $parcel):
                $parcel->is_paid = false;
                $parcel->withdraw_id = null;
                $parcel->save();
            endforeach;

            foreach ($merchant_withdraw->merchantAccounts as $merchant_accounts):
                $merchant_accounts->payment_withdraw_id = null;
                $merchant_accounts->save();
            endforeach;

            // merchant sms start
            $sms_template = WithdrawSmsTemplate::where('subject','payment_cancelled_event')->first();

            $sms_body = str_replace('{account_details}', $merchant_withdraw->account_details, $sms_template->content);
            $sms_body = str_replace('{amount}', $merchant_withdraw->amount, $sms_body);
            $sms_body = str_replace('{our_company_name}', __('app_name'), $sms_body);
            $sms_body = str_replace('{current_date_time}', date('M d, Y h:i a'), $sms_body);
            $sms_body = str_replace('{payment_id}', $merchant_withdraw->withdraw_id, $sms_body);

            if($sms_template->sms_to_merchant):
                $this->smsSender('payment_cancelled_event', $merchant_withdraw->merchant->phone_number, $sms_body, $sms_template->masking);
            endif;
            //merchant sms end

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function fileUpload($image){

        $requestImage           = $image;
        $fileType               = $requestImage->getClientOriginalExtension();

        $original   = date('YmdHis') .'-receipt' . rand(1, 50) . '.' . $fileType;

        $directory              = 'admin/images/';

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

    public function removeOldFile($image)
    {
        if($image != "" && file_exists($image)):
            unlink($image);
        endif;
    }

    public function generate_random_string($length=10) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
}
