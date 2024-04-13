<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;

if (!function_exists('sendMail')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function sendMail($user, $code, $purpose, $password = '')
    {

        if($purpose       == 'verify_email'):
            $url = url('/') . '/activation/' . $user->email . '/' . $code;
            $view = 'merchant.auth.mail.activate-account-email';
            $subject = __('verify_email_subject');
        elseif($purpose   == 'forgot_password'):
            $url = url('/') . '/reset/' . $user->email . '/' . $code;
            $view = 'admin.auth.mail.forgot-password-email';
            $subject = __('reset_password_subject');
        elseif($purpose   == 'verify_email_success'):
            $url = '';
            $view = 'merchant.auth.mail.registration-success-email';
            $subject = __('verify_email_success_subject');
        else:
            $url = '';
            $view = 'admin.auth.mail.reset-success-email';
            $subject = __('reset_password_success_subject');
        endif;

        $data = ['url' => $url, 'user' => $user];



        Mail::send($view, [
            'data' => $data
        ], function ($message) use ($user, $subject) {
            $message    ->to([$user->email]);
            $message    ->replyTo('info@greenx.com.bd', __('app_name'));
            $message    ->subject($subject);
        });
    }
}

if (!function_exists('sendMailTo')) {

    function sendMailTo($email, $data)
    {
        Mail::send('setting::email.email_template', [
            'templateBody' => $data->message
        ], function ($message) use ($email, $data) {
            $message    ->to($email);
            $message    ->replyTo('info@greenx.com.bd', __('app_name'));
            $message    ->subject($data->subject);
        });
    }
}

function hasPermission($key_word)
{

    if(in_array($key_word, Sentinel::getUser()->permissions)){
        return true;
    }

    return false;
}

if (!function_exists('defaultModeCheck')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function defaultModeCheck()
    {
        $mode       = Session::get('mode');
        if ($mode   == "") :
            Session::put('mode', '');
        endif;
        return Session::get('mode');

    }
}
