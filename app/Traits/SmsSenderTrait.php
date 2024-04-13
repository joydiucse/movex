<?php

namespace App\Traits;
use SoapClient;
trait SmsSenderTrait {

    public function smsSender($title, $phone_number, $sms_body, $masking = false)
    {
//        return true;
//        $phone_number = '01620601859';
        if(preg_match('/^((01958))/',$phone_number)):
            return true;
        endif;
        $phone_number = preg_replace('/^(\+88|88)/', '', $phone_number);
        $phone_number = preg_replace('/-/', '', $phone_number);
        $phone_number = preg_replace('/(\s)/', '', $phone_number);

        $matched = preg_match('/^((017)|(013))/',$phone_number);

        $success = false;

        $mask_name = '';
        if ($masking):
            $mask_name = settingHelper('mask_name') ? settingHelper('mask_name') : '';
        endif;

        if (settingHelper('sms_provider') == 'gp'):
            $url = settingHelper('gp_sms_url');
            $gp_data = array(
                "username"      => settingHelper('gp_username'),
                "password"      => settingHelper('gp_password'),
                "apicode"       =>"1",
                "msisdn"        => $phone_number,
                "countrycode"   => "880",
                "cli"           => $mask_name,
                "messagetype"   => "3",
                "message"       => $sms_body,
                "messageid"     => "0"
            );

            $data_string = json_encode($gp_data);
            $ch=\curl_init($url);

            \curl_setopt_array($ch, array(
                    CURLOPT_POST            => true,
                    CURLOPT_POSTFIELDS      => $data_string,
                    CURLOPT_HEADER          => false,
                    CURLOPT_RETURNTRANSFER  => true,
                    CURLOPT_HTTPHEADER      => array('Content-Type:application/json', 'Content-Length: ' . strlen($data_string)))
            );

            $result = \curl_exec($ch);

            \curl_close($ch);

            $success = strstr($result, '200') !== false;

        elseif (settingHelper('sms_provider') == 'soap'):
            $soapClient = new SoapClient(settingHelper('soap_url'));

            $paramArray = array(
                'userName'      => settingHelper('soap_username'),
                'userPassword'  => settingHelper('soap_password'),
                'mobileNumber'  => $phone_number,
                'smsText'       => $sms_body,
                'type'          => "TEXT",
                'maskName'      => $mask_name,
                'campaignName'  => '',
            );
            $value      = $soapClient->__call("OneToOne", array($paramArray));

            $success    = strpos($value->OneToOneResult, '1900');

        elseif (settingHelper('sms_provider') == 'reve'):
            $url        = settingHelper('reve_url');
            $ajura_data = array(
                "apikey"        => settingHelper('reve_api_key'),
                "secretkey"     => settingHelper('reve_secret'),
                "callerID"      => $masking ? $mask_name: 1234,
                "toUser"        => $phone_number,
                "messageContent"=> $sms_body
            );

            $ch     = \curl_init();

            $data   = http_build_query($ajura_data);
            $getUrl = $url."?".$data;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $getUrl);
            curl_setopt($ch, CURLOPT_TIMEOUT, 80);

            $result     = \curl_exec($ch);

            \curl_close($ch);

            $success    = strstr($result, 'ACCEPTD') !== false;
        endif;

        if ($success === false):
            return false;
        else:
            return true;
        endif;
    }

    public function smsSenderBulk($title, $phone_number, $sms_body)
    {
        $phone_number = preg_replace('/^(\+88|88)/', '', $phone_number);
        $phone_number = preg_replace('/-/', '', $phone_number);

        $mask_name = settingHelper('mask_name') ? settingHelper('mask_name') : '';

        $url = settingHelper('gp_sms_url');
        $gp_data = array(
            "username"      => settingHelper('gp_username'),
            "password"      => settingHelper('gp_password'),
            "apicode"       =>"6",
            "msisdn"        => $phone_number,
            "countrycode"   => "880",
            "cli"           => $mask_name,
            "messagetype"   => "3",
            "message"       => $sms_body,
            "messageid"     => "0"
        );

        $data_string    = json_encode($gp_data);
        $ch             =\curl_init($url);

        \curl_setopt_array($ch, array(
                CURLOPT_POST            => true,
                CURLOPT_POSTFIELDS      => $data_string,
                CURLOPT_HEADER          => false,
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_HTTPHEADER      => array('Content-Type:application/json', 'Content-Length: ' . strlen($data_string)))
        );

        $result     = \curl_exec($ch);

        \curl_close($ch);

        $success    = strstr($result, '200') !== false;

        if ($success === false):
            return false;
        else:
            return true;
        endif;
    }

    public function smsBulkSMSByReve($title, $phone_number, $sms_body,$masking=true)
    {
        try {

            $phone_number = preg_replace('/^(\+88|88)/', '', $phone_number);
            $phone_number = preg_replace('/-/', '', $phone_number);
             // dd($phone_number);

            $mask_name = 'GreenX';

            $url        = 'http://smpp.ajuratech.com:7788/sendtext';
            $ajura_data = array(
                "apikey"        => settingHelper('reve_api_key'),
                "secretkey"     => settingHelper('reve_secret'),
                "callerID"      => $masking ? $mask_name: 1234,
                "toUser"        => $phone_number,
                "messageContent"=> $sms_body
            );

            $ch     = \curl_init();

            $data   = http_build_query($ajura_data);
            $getUrl = $url."?".$data;
            dd($getUrl);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $getUrl);
            curl_setopt($ch, CURLOPT_TIMEOUT, 80);

            $result     = \curl_exec($ch);

            \curl_close($ch);


            $success    = strstr($result, 'ACCEPTD') !== false;


            if ($success === false):
                return false;
            else:
                return true;
            endif;
        } catch (Exception $e) {
            dd($e);

        }
    }

}
