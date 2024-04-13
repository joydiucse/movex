<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\Admin\ExpenseInterface;
use App\Repositories\Interfaces\Admin\BankAccountInterface;
use App\Http\Requests\Admin\Expense\ExpenseStoreRequest;
use App\Models\Account\Account;
use App\Models\Account\CompanyAccount;

class ExpenseController extends Controller
{
    protected $expenses;
    protected $accounts;
    public function __construct(ExpenseInterface $expenses,BankAccountInterface $accounts)
    {
        $this->expenses    = $expenses;
        $this->accounts    = $accounts;

    }
    public function index()
    {
        $expenses = $this->expenses->all();
        return view('admin.accounts.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $accounts         = $this->accounts->all()->where('user_id', \Sentinel::getUser()->id);
        return view('admin.accounts.expenses.create', compact('accounts'));
    }

    public function store(ExpenseStoreRequest $request)
    {

        if(str_replace(',', '', $request->payable_balance) < $request->amount){
            return back()->with('danger', __('the_account_has_no_available_balance'));
        }
        if($this->expenses->store($request)):
            return redirect()->route('expenses')->with('success', __('created_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function edit($id)
    {
        $expense          = $this->expenses->get($id);
        $accounts         = $this->accounts->all()->where('user_id', \Sentinel::getUser()->id);

        $balance  = number_format($this->accounts->bankRemainingBalance('company_accounts', '', @$id, 'edit'),2);

        return view('admin.accounts.expenses.edit', compact('expense', 'accounts', 'balance'));
    }

    public function update(ExpenseStoreRequest $request)
    {
        $balance  = $this->accounts->bankRemainingBalance('company_accounts', @$request->account, @$request->id, 'update');

        if($balance < $request->amount){
            return back()->with('danger', __('the_account_has_no_available_balance'));
        }

        if($this->expenses->update($request)):
            return redirect()->route('expenses')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }

    public function delete($id)
    {
        if($this->expenses->delete($id)):
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
}
