<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Users\UserUpdateRequest;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\LogActivity;
use App\Models\User;
use App\Models\Account\CompanyAccount;
use Sentinel;
use App\Repositories\Interfaces\Admin\BankAccountInterface;

class CommonController extends Controller
{
    protected $users;
    protected $bank_accounts;

    public function __construct(UserInterface $users, BankAccountInterface $bank_accounts)
    {
        $this->users                   = $users;
        $this->bank_accounts           = $bank_accounts;

    }
    public function deleteModal($id)
    {
    	return 'cdfsds';
    }

    public function modeChange()
    {
        $mode               = Session::get('mode');
        if($mode == 'dark-mode'):
            Session::put('mode', 'light-mode');
        else:
            Session::put('mode', 'dark-mode');
        endif;
        return response()->json('success');
    }

    public function profile()
    {
        return view('common.profile.staff.staff-profile');
    }

    public function paymentLogs()
    {
        $statements = CompanyAccount::where('user_id', Sentinel::getUser()->id)->orderby('id', 'desc')->paginate(\Config::get('greenx.paginate'));
        return view('common.profile.staff.payment-logs', compact('statements'));
    }

    public function notification()
    {
        return view('common.profile.staff.staff-notification');
    }

    public function accountActivity()
    {
        $login_activities = LogActivity::where('user_id', \Sentinel::getUser()->id)->orderBy('id', 'desc')->limit(20)->get();
        return view('common.profile.staff.staff-account-activity', compact('login_activities'));
    }

    public function securitySetting()
    {
        return view('common.profile.staff.staff-security-settings');
    }


    public function changePassword(Request $request)
    {
        $hasher         = Sentinel::getHasher();

        $current_password    = $request->current_password;
        $password       = $request->new_password;
        $user           = Sentinel::getUser();


        if (!$hasher->check($current_password, $user->password)) {
            return back()->with('danger', __('current_password_is_invalid'));
        }

        $user = User::find(Sentinel::getUser()->id);
        $user->password = bcrypt($password);
        $user->last_password_change = date('Y-m-d H:i:s');
        $user->save();

        return back()->with('success', __('updated_successfully'));
    }

    public function profileUpdate(UserUpdateRequest $request){

        $request->id = Sentinel::getUser()->id;

        if($this->users->updateProfile($request)):
            return back()->with('success', __('updated_successfully'));
        else:
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        endif;

    }

    public function getBalanceInfo(Request $request)
    {
        $balance  = number_format($this->bank_accounts->bankRemainingBalance(@$request->table_name, @$request->id, @$request->row_id, @$request->purpose), 2);
        return response()->json($balance);
    }

    public function getAccounts(Request $request)
    {
        return response()->json($this->bank_accounts->accountsByUser($request->id));
    }

    public function logoutOtherDevices()
    {
        $user = Sentinel::getUser();

        if(Sentinel::logout(null, true)):
            Sentinel::authenticate($user);

            $success[0] = __('logout_successfully');
            $success[1] = 'success';
            $success[2] = __('logout');
            return response()->json($success);
        else:
            $success[0] = __('something_went_wrong_please_try_again');
            $success[1] = 'error';
            $success[2] = __('oops');
            return response()->json($success);
        endif;
    }

    public function userAccounts()
    {
        $accounts = Sentinel::getUser()->accounts(Sentinel::getUser()->id);
        return view('common.profile.staff.accounts', compact('accounts'));
    }

}
