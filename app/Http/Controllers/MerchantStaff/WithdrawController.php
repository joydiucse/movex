<?php

namespace App\Http\Controllers\MerchantStaff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Merchant\OthersAccountRequest;
use App\Http\Requests\Merchant\WithdrawStoreRequest;
use App\Http\Requests\Merchant\WithdrawUpdateRequest;
use App\Models\Account\MerchantWithdraw;
use App\Repositories\Interfaces\WithdrawInterface;
use App\Traits\CommonHelperTrait;
use App\Traits\MerchantBalanceTrait;
use Illuminate\Http\Request;
use Sentinel;

class WithdrawController extends Controller
{
    use MerchantBalanceTrait, CommonHelperTrait;

    protected $withdraws;

    public function __construct(WithdrawInterface $withdraws)
    {
        $this->withdraws = $withdraws;
    }

    public function index(){
        $withdraws = Sentinel::getUser()->staffMerchant->withdraws()
            ->when(!hasPermission('all_parcel_payment'), function ($query){
                $query->whereHas('companyAccount', function ($q){
                    $q->where('created_by', Sentinel::getUser()->id);
                });
            })
            ->paginate(\Config::get('greenx.paginate'));
        return view('merchant.withdraw.index', compact('withdraws'));
    }

    public function paymentAccounts(){
        $payment_account = Sentinel::getUser()->staffMerchant->paymentAccount;
        return view('merchant.payment.bank_account', compact('payment_account'));
    }

    public function paymentBankUpdate(Request $request){
        if($this->withdraws->updateBankDetails($request)):
            return redirect()->route('merchant.staff.payment.accounts')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function paymentOthersAccountUpdate(OthersAccountRequest $request){
        if($this->withdraws->updateOthersAccount($request)):
            return redirect()->route('merchant.staff.payment.accounts')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function create(){
        if (@settingHelper('preferences')->where('title','create_payment_request')->first()->merchant):
            $payment_accounts = Sentinel::getUser()->staffMerchant->paymentAccount;

            $data = $this->staffMerchantBalance(Sentinel::getUser()->merchant_id);

            $current_payable    = $data['current_payable'];
            $parcels            = $data['parcels'];
            $merchant_accounts  = $data['merchant_accounts'];

            if ($current_payable <= 0 ):
                return redirect()->back()->with('danger', __('you_do_not_have_enough_amount_to_make_a_payment_request'));
            endif;

            // $payment_account[] = 'cash';

            $payment_account = [];

            if ($payment_accounts->selected_bank != '' &&  $payment_accounts->bank_branch != '' && $payment_accounts->bank_ac_name != '' && $payment_accounts->bank_ac_number != ''):
                $payment_account[] =  'bank';
            endif;

            if ($payment_accounts->bkash_number != '' &&  $payment_accounts->bkash_ac_type != ""):
                $payment_account[] =  'bKash';
            endif;

            if ($payment_accounts->rocket_number != '' &&  $payment_accounts->rocket_ac_type != ""):
                $payment_account[] =  'rocket';
            endif;

            if ($payment_accounts->nogod_number != '' &&  $payment_accounts->nogod_ac_type != ""):
                $payment_account[] =  'nogod';
            endif;

            return view('merchant.withdraw.create', compact('payment_account','current_payable','parcels','merchant_accounts'));
        else:
            return back()->with('danger', __('service_unavailable'));
        endif;
    }

    public function store(WithdrawStoreRequest $request){
        $payment_accounts = Sentinel::getUser()->staffMerchant->paymentAccount;

        if(!$this->checkRoutingNo($payment_accounts->routing_no, $request->withdraw_to)):
            if (@settingHelper('preferences')->where('title','create_payment_request')->first()->merchant):

                $data = $this->staffMerchantBalance(Sentinel::getUser()->merchant_id);

                $current_payable    = $data['current_payable'];

                if (number_format($current_payable, 2, '.', '') != number_format($request->amount, 2, '.', '')):
                    return back()->with('danger', __('incorrect_amount_please_try_again'));
                else:
                    if($this->withdraws->store($request)):
                        return redirect()->route('merchant.staff.withdraw')->with('success', __('created_successfully'));
                    else:
                        return back()->with('danger', __('something_went_wrong_please_try_again'));
                    endif;
                endif;
            else:
                return redirect()->route('merchant.staff.withdraw')->with('danger', __('service_unavailable'));
            endif;
        else:
            return back()->with('danger', __('please_add_routing_no_to_your_bank_account'));
        endif;
    }

    public function edit($id){
        if ( $id <= 1939):
            return back()->with('danger', __('you_are_not_allowed_to_update_this_withdraw'));
        endif;

        $payment_accounts = Sentinel::getUser()->staffMerchant->paymentAccount;

        $withdraw = $this->withdraws->get($id);

        if($withdraw->status == 'pending' && ($withdraw->companyAccount->created_by == Sentinel::getUser()->id || ($withdraw->merchant_id == Sentinel::getUser()->merchant_id && hasPermission('all_parcel_payment')))):
            // $payment_account[] = 'cash';
            $payment_account = [];

            if ($payment_accounts->selected_bank != '' &&  $payment_accounts->bank_branch != '' && $payment_accounts->bank_ac_name != '' && $payment_accounts->bank_ac_number != ''):
                $payment_account[] =  'bank';
            endif;

            if ($payment_accounts->bkash_number != '' &&  $payment_accounts->bkash_ac_type):
                $payment_account[] =  'bKash';
            endif;

            if ($payment_accounts->rocket_number != '' &&  $payment_accounts->rocket_ac_type):
                $payment_account[] =  'rocket';
            endif;

            if ($payment_accounts->nogod_number != '' &&  $payment_accounts->nogod_ac_type):
                $payment_account[] =  'nogod';;
            endif;

            return view('merchant.withdraw.edit', compact('withdraw','payment_account'));
        else:
            return back()->with('danger', __('you_are_not_allowed_to_update_this_withdraw'));
        endif;
    }

    public function update(WithdrawUpdateRequest $request){
        if ( $request->id <= 1939):
            return back()->with('danger', __('you_are_not_allowed_to_update_this_withdraw'));
        endif;

        $payment_accounts = Sentinel::getUser()->staffMerchant->paymentAccount;

        if(!$this->checkRoutingNo($payment_accounts->routing_no, $request->withdraw_to)):
            $merchant_withdraw                 = MerchantWithdraw::find(Sentinel::getUser()->staffMerchant->id);
            if (!$merchant_withdraw->withdrawBatch):
                $current_payable = $this->withdrawUpdateMerchantBalance($request->id);

                if (number_format($current_payable, 2, '.', '') != number_format($request->amount, 2, '.', '')):
                    return back()->with('danger', __('incorrect_amount_please_try_again'));
                else:
                    if($this->withdraws->update($request)):
                        return redirect()->route('merchant.staff.withdraw')->with('success', __('updated_successfully'));
                    else:
                        return back()->with('danger', __('something_went_wrong_please_try_again'));
                    endif;
                endif;
            else:
                return redirect()->route('merchant.withdraw')->with('danger', __('you_are_not_allowed_to_update_this_withdraw'));
            endif;
        else:
            return back()->with('danger', __('please_add_routing_no_to_your_bank_account'));
        endif;
    }

    public function changeStatus($id, $status)
    {
        $withdraw = $this->withdraws->get($id);

        if ( $id <= 1939):
            $success[0] = __('you_are_not_allowed_to_cancel_this_withdraw');
            $success[1] = 'error';
            $success[2] = __('oops');
            return response()->json($success);
        endif;

        if($withdraw->status == 'pending' && ($withdraw->companyAccount->created_by == Sentinel::getUser()->id || ($withdraw->merchant_id == Sentinel::getUser()->merchant_id && hasPermission('all_parcel_payment')))):
            if($this->withdraws->chargeStatus($id, $status)):
                $success[0] = __('updated_successfully');
                $success[1] = 'success';
                $success[2] = __('updated');
                return response()->json($success);
            else:
                $success[0] = __('something_went_wrong_please_try_again');
                $success[1] = 'error';
                $success[2] = __('oops');
                return response()->json($success);
            endif;
        else:
            $success[0] = __('you_are_not_allowed_to_update_this_withdraw');
            $success[1] = 'error';
            $success[2] = __('oops');
            return response()->json($success);
        endif;

    }

    public function invoice($id)
    {
        $withdraw = $this->withdraws->get($id);

        if($withdraw->created_by == Sentinel::getUser()->id || ($withdraw->merchant_id == Sentinel::getUser()->merchant_id && hasPermission('all_parcel_payment'))):
            return view('merchant.withdraw.invoice', compact('withdraw'));
        else:
            return back()->with('danger', __('unable_to_access'));
        endif;
    }

    public function invoicePrint($id)
    {
        $withdraw = $this->withdraws->get($id);

        if($withdraw->created_by == Sentinel::getUser()->id || ($withdraw->merchant_id == Sentinel::getUser()->merchant_id && hasPermission('all_parcel_payment'))):
            return view('merchant.withdraw.print', compact('withdraw'));
        else:
            return back()->with('danger', __('unable_to_access'));
        endif;
    }
}
