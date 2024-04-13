<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Sentinel;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\Auth\LoginPostRequest;
use App\Http\Requests\Admin\Auth\ForgotPasswordPostRequest;
use App\Http\Requests\Admin\Auth\ResetPasswordPostRequest;
use Reminder;
use App\Models\LogActivity as LogActivityModel;


class AuthController extends Controller
{
    public function loginForm()
    {
    	return view('admin.auth.login');
    }

    public function login(LoginPostRequest $request)
    {

    	$user = User::where('email', $request->email)->first();

        if (blank($user)) {
            return redirect()->back()->withInput()->with('danger', __('your_email_is_invalid'));
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
    	$log['url']        = \Request::fullUrl();
    	$log['method']     = \Request::method();
    	$log['ip']         = \Request::ip();
    	$log['browser']    = $this->getBrowser(\Request::header('user-agent'));
    	$log['platform']   = $this->getPlatForm(\Request::header('user-agent'));
    	$log['user_id']    = $user->id;
    	LogActivityModel::create($log);

        $remember_me = $request->has('remember_me') ? true : false;

        Sentinel::authenticate($request->all(), $remember_me);

        if($user->user_type == "staff") {
            return redirect()->route('dashboard');
        }else{
            return redirect()->route('merchant.dashboard');
        }


    }

    public function logout()
    {
        Sentinel::logout();
        return redirect()->route('admin.login');
    }

    public function forgotPassword()
    {
        return view('admin.auth.forgot_password');
    }

    public function forgotPasswordPost(ForgotPasswordPostRequest $request)
    {

        $user       = User::whereEmail($request->email)->first();

        if(blank($user)){
            return redirect()->back()->with([
                'danger' => __('invalid_email'),
            ]);
        }

        if (Reminder::exists($user)) :
            $remainder = Reminder::where('user_id', $user->id)->first();
        else :
            $remainder = Reminder::create($user);
        endif;
        //send a mail to user
        sendMail($user, $remainder->code, 'forgot_password', '');

        return redirect()->back()->with([
            'success' => __('reset_link_is_send_to_mail'),
        ]);
    }

    public function resetPassword($email, $resetCode)
    {
        $user       = User::byEmail($email);

        if ($reminder = Reminder::exists($user, $resetCode)) :
            return view('admin.auth.reset-password', ['email' => $email, 'resetCode' => $resetCode]);
        else :
            return redirect()->route('login');
        endif;
    }

    public function PostResetPassword(ResetPasswordPostRequest $request, $email, $resetCode)
    {

        $user       = User::byEmail($email);
        if ($reminder = Reminder::exists($user, $resetCode)) {

            Reminder::complete($user, $resetCode, $request->password);
            sendMail($user, '', 'reset_password', $request->password);

            return redirect()->route('login')->with('success', __('you_can_login_with_new_password'));
        } else {
            return redirect()->route('login');
        }

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
