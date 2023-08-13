<?php

namespace App\Models\Sms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\Http;

class UnifonicSms extends Model
{
    use HasFactory;

    /**
     * Sends sms to user using Twilio's programmable sms client
     * @param Number $recipients string or array of phone number of recepient
     * @param String $message Body of sms
     */
    public static function sendMessage($recipients,$message)
    {
        $request = array();
        if (!config('app_settings.enable_sms_notifications.value')) {
            return true;
        }
        try
        {
            $account_sid = config('sms.unifonic.unifonic_sid');
            $auth_token = config('sms.unifonic.unifonic_auth_token');
            $url = config('sms.unifonic.unifonic_url');

            $request['phone'] = $recipients;
            $request['discription'] = $message;

        $response = Http::post($url, [
            'AppSid' => $auth_token,
            'SenderID' => $account_sid,
            'Recipient' => $recipients,
            'Body' => $message,
            'responseType' => 'JSON',
            'baseEncode' => true,
            'statusCallback' => 'sent',
            'async' => false,
        ]);
        if ($response['success']) {
            $request['response_code'] = $response['errorCode'];
            $request['status'] = true;
        }else{
            $request['response_code'] = $response['errorCode'];
            $request['status'] = false;
        }
        }catch (Exception $e)
            {
                $request['status'] = false;
                $request['response_code'] = $e->getMessage();
            }
        SmsNotification::create($request);
        }
    }
