<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account\Account;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\Admin\BankAccountInterface;
use App\Models\StaffAccount;
use App\Repositories\Interfaces\UserInterface;

class BankAccountController extends Controller
{
    protected $accounts;
    protected $users;

    public function __construct(UserInterface $users, BankAccountInterface $accounts)
    {
        $this->accounts = $accounts;
        $this->users    = $users;
    }

    public function index()
    {
        $accounts = $this->accounts->paginate();
        return view('admin.accounts.accounts.index', compact('accounts'));
    }

    public function create()
    {
        $users    = $this->users->all()->where('user_type', 'staff');
        return view('admin.accounts.accounts.create', compact('users'));
    }

    public function store(Request $request)
    {
        if($this->accounts->store($request)):
            return redirect()->route('admin.account')->with('success', __('created_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function edit($id)
    {
        $account = $this->accounts->get($id);
        $users    = $this->users->all()->where('user_type', 'staff');
        return view('admin.accounts.accounts.edit', compact('account', 'users'));
    }

    public function update(Request $request)
    {
        if($this->accounts->update($request)):
            return redirect()->route('admin.account')->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function delete($id)
    {
        if($this->accounts->delete($id)):
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

    public function statement($id)
    {
        $accounts    = StaffAccount::with('fundTransfer')->where('account_id', $id)->orWhere('from_account_id', $id)->orWhere('to_account_id', $id)->orderBy('id', 'desc')->paginate(\Config::get('greenx.paginate'));
        $account     = Account::find($id);
        $grand_total = $account->incomes()->sum('amount') + $account->fundReceives()->sum('amount') - $account->expenses()->sum('amount') - $account->fundTransfers()->sum('amount');
        return view('admin.accounts.accounts.statement', compact('accounts', 'grand_total'));
    }

    public function staffStatement($id)
    {
        $accounts    = StaffAccount::with('fundTransfer')->where('account_id', $id)->orWhere('from_account_id', $id)->orWhere('to_account_id', $id)->orderBy('id', 'desc')->paginate(\Config::get('greenx.paginate'));
        $account     = Account::find($id);
        if (\Sentinel::getUser()->id == $account->user_id):
            $grand_total = $account->incomes()->sum('amount') + $account->fundReceives()->sum('amount') - $account->expenses()->sum('amount') - $account->fundTransfers()->sum('amount');
            return view('admin.accounts.accounts.statement', compact('accounts', 'grand_total'));
        else:
            return back()->with('danger', __('access_denied'));
        endif;

    }
}
