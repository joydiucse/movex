<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Account\CreditRequest;
use App\Models\Parcel;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\DeliveryManInterface;
use App\Repositories\Interfaces\ParcelInterface;
use App\Repositories\Interfaces\AccountInterface;
use App\Http\Requests\Admin\Account\CompanyAccountStoreRequest;
use App\Repositories\Interfaces\Admin\BankAccountInterface;

class AccountController extends Controller
{
    protected $company_accounts;
    protected $parcels;
    protected $delivery_man;
    protected $accounts;

    public function __construct(AccountInterface $company_accounts,ParcelInterface $parcels,DeliveryManInterface $delivery_man,BankAccountInterface $accounts)
    {
        $this->company_accounts = $company_accounts;
        $this->parcels          = $parcels;
        $this->delivery_man     = $delivery_man;
        $this->accounts         = $accounts;
    }

    public function index()
    {
        $company_accounts = $this->company_accounts->paginate(\Config::get('greenx.paginate'));
        return view('admin.accounts.index', compact('company_accounts'));
    }

    public function create()
    {
        $accounts         = $this->accounts->all()->where('user_id', \Sentinel::getUser()->id);
        return view('admin.accounts.create', compact( 'accounts'));
    }

    public function store(CompanyAccountStoreRequest $request)
    {
        if($this->company_accounts->store($request)):
            return redirect()->route('incomes')->with('success', __('created_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }
    public function edit($id)
    {
        $company_account  = $this->company_accounts->get($id);
        $accounts         = $this->accounts->all()->where('user_id', \Sentinel::getUser()->id);
        $delivery_man     = $this->delivery_man->get($company_account->delivery_man_id);
        $delivery_man_current_balnace = $delivery_man->balance($company_account->delivery_man_id);
        $current_amount   = __('current_balance').': '.number_format($company_account->amount + $delivery_man_current_balnace,2) .' '.__('tk');
        return view('admin.accounts.edit', compact('company_account', 'accounts','current_amount'));
    }

    public function update(CompanyAccountStoreRequest $request)
    {
        if($this->company_accounts->update($request)):
            return redirect()->route('incomes')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }

    public function delete($id)
    {
        if($this->company_accounts->delete($id)):
            $success[0] = __('deleted_successfully');
            $success[1] = 'success';
            $success[2] = __('deleted');
            return response()->json($success);
        else:
            $success[0] = __('something_went_wrong_please_try_again');
            $success[1] = 'error';
            $success[2] = __('oops');
            return response()->json($success);
        endif;
    }

    public function creditCreate()
    {
        
        $accounts         = $this->accounts->all()->where('user_id', \Sentinel::getUser()->id);
        return view('admin.accounts.credit.create', compact('accounts'));
    }

    public function creditStore(CreditRequest $request)
    {
        if($this->company_accounts->creditStore($request)):
            return redirect()->route('incomes')->with('success', __('created_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }

    public function merchantParcels(Request $request)
    {
        $parcels = Parcel::where('merchant_id', $request->merchant_id)->when(!hasPermission('read_all_parcel'), function ($query){
            $query->where(function ($q){
                $q->where('hub_id', \Sentinel::getUser()->hub_id)
                    ->orWhere('pickup_hub_id', \Sentinel::getUser()->hub_id)
                    ->orWhereNull('pickup_hub_id')
                    ->orWhere('transfer_to_hub_id', \Sentinel::getUser()->hub_id);
            });
        })->get();

        return view('admin.accounts.credit.parcel-options', compact('parcels'))->render();
    }

    public function creditEdit($id)
    {
        $company_account    = $this->company_accounts->get($id);

        if ($company_account->create_type=='user_defined' && ($company_account->merchantAccount->source == "cash_given_for_delivery_charge" && ($company_account->merchantAccount->payment_withdraw_id == null || $company_account->merchantAccount->is_paid == false))):
            $accounts           = $this->accounts->all()->where('user_id', \Sentinel::getUser()->id);
            return view('admin.accounts.credit.edit', compact('company_account',  'accounts'));
        else:
            return back()->with('danger', __('you_are_not_allowed_to_update_this_anymore'));
        endif;

    }

    public function creditUpdate(CreditRequest $request)
    {
        if($this->company_accounts->creditUpdate($request)):
            return redirect()->route('incomes')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function balance(Request $request)
    {
        $delivery_man    = $this->delivery_man->get($request->id);
        $data = [];
        if ($request->data_for == 'update'):
            $company_account  = $this->company_accounts->get($request->company_account_id);
            if ($request->id == $company_account->delivery_man_id):
                $delivery_man     = $this->delivery_man->get($company_account->delivery_man_id);
                $delivery_man_current_balnace = $delivery_man->balance($company_account->delivery_man_id);
                $data['balance']   = __('current_balance').': '.number_format($company_account->amount + $delivery_man_current_balnace,2) .' '.__('tk');
            else:
                $data['balance']   = __('current_balance').': '.number_format($delivery_man->balance($delivery_man->id),2) .' '.__('tk');
            endif;
        else:
            $data['balance']   = __('current_balance').': '.number_format($delivery_man->balance($delivery_man->id),2) .' '.__('tk');
        endif;
        return response()->json($data);
    }
}
