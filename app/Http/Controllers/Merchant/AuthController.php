<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginPostRequest;
use App\Http\Requests\Admin\Merchant\MerchantStoreRequest;
use App\Models\LogActivity as LogActivityModel;
use App\Models\TempStore;
use App\Models\User;
use App\Repositories\Interfaces\Merchant\MerchantInterface;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Sentinel;

class AuthController extends Controller
{
    protected $merchants;

    public function __construct(MerchantInterface $merchants)
    {
        $this->merchants    = $merchants;

    }

    public function loginForm()
    {
        return view('merchant.auth.login');
    }
    public function login(LoginPostRequest $request)
    {
        $user = User::where('email', $request->email)->orWhere('phone_number', $request->email)->first();

        if (blank($user)) {
            return redirect()->back()->withInput()->with('danger', __('your_email_or_phone_is_invalid'));
        }

        if($user->status == 0) {
            return redirect()->back()->withInput()->with('danger', __('your_account_is_inactive'));
        } elseif($user->status == 2) {
            return redirect()->back()->withInput()->with('danger', __('your_account_is_suspend'));
        }

        if (!Hash::check($request->get('password'), $user->password)) {
            return redirect()->back()->withInput()->with('danger', __('invalid_credentials'));
        }

        $log = [];
        $log['url'] = \Request::fullUrl();
        $log['method'] = \Request::method();
        $log['ip'] = \Request::ip();
        $log['browser'] = $this->getBrowser(\Request::header('user-agent'));
        $log['platform'] = $this->getPlatForm(\Request::header('user-agent'));
        $log['user_id'] = $user->id;
        LogActivityModel::create($log);

        $remember_me = $request->has('remember_me') ? true : false;

        Sentinel::authenticate($user, $remember_me);

        if($user->user_type == "staff") {
            return redirect()->route('dashboard');
        }elseif($user->user_type == "merchant"){
            return redirect()->route('merchant.dashboard');
        }elseif($user->user_type == "merchant_staff"){
            return redirect()->route('merchant.staff.dashboard');
        }

    }

    public function logout()
    {
        Sentinel::logout();
        return redirect()->route('merchant.login');
    }

    public function registerForm()
    {
        return view('merchant.auth.register');
    }

    public function register(MerchantStoreRequest $request){
        if ($id = $this->merchants->tempStore($request)):
            if($id == 'false'):
                return back()->withInput()->with('danger', __('unable_to_send_otp'));
            else:
                $success = __('check_your_phone_for_otp');
                return view('merchant.auth.confirm-otp',compact('id','success'));
            endif;
        else:
            return back()->withInput()->with('danger', __('something_went_wrong_please_try_again'));
        endif;
    }

    public function otpConfirm(){
        return view('errors.404');
    }

    public function otpConfirmPost(Request $request){
        if ($user = $this->merchants->otpConfirm($request)):

//          after successfully registered auto login merchant
            $log = [];
            $log['url'] = \Request::fullUrl();
            $log['method'] = \Request::method();
            $log['ip'] = \Request::ip();
            $log['browser'] = $this->getBrowser(\Request::header('user-agent'));
            $log['platform'] = $this->getPlatForm(\Request::header('user-agent'));
            $log['user_id'] = $user->id;
            LogActivityModel::create($log);

            Sentinel::authenticate($user);
            return redirect()->route('merchant.dashboard')->with('success', __('registration_successful'));
        else:
            $id = $request['id'];
            $danger = __('otp_mismatch');
            return view('merchant.auth.confirm-otp',compact('id','danger'));
        endif;
    }

    public function otpRequest($id){
        if ($this->merchants->resendOtp($id)):
            $success = __('we_have_send_you_another_otp');
        else:
            $danger = __('unable_to_send_otp');
            return response()->json($danger);
        endif;
    }

    public function activation($email, $activationCode)
    {
        $user       = User::whereEmail($email)->first();

        sendMail($user, '', 'verify_email_success', '');

        return redirect()->route('login')->with('success', __('email_verified_successfully'));
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
}
