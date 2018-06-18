<?php

namespace Hni\Dwsync\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\DB;

/**
 * Class DwSms
 * @package Hni\Dwsync\Models
 * @version September 21, 2017, 1:31 pm UTC
 *
 * @property \Hni\Dwsync\Models\DwSms dwSms
 * @property string date
 * @property string recipient
 * @property string content
 * @property string status
 * @property integer curl_error_no
 * @property string curl_error
 */
class DwSms extends Model
{

    public $table = 'dw_sms';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public $fillable = [
        'date',
        'recipient',
        'content',
        'status',
        'curl_error_no',
        'curl_error'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'string',
        'recipient' => 'string',
        'content' => 'string',
        'status' => 'string',
        'curl_error_no' => 'integer',
        'curl_error' => 'string'
    ];

    public static function sendSMS($recipientList, $smsContent, $forceTo160 = true)
    {
        $url = str_replace("[sms_account]", config('dwsync.account.sms'), config('dwsync.url.smsApi'));
        $no_space_list = preg_replace('/\s+/', '', $recipientList);
        $smsContent = str_replace(array("\n", "\r", "\t", "\v"), "", $smsContent);//preg_replace doesn't work correctly with Metachars
        if($forceTo160)
            $smsContent = substr($smsContent, 0, 159);//maxi 160 car

        // Make Post Fields Array
        $data_string = [
            'numbers' => explode(",", $no_space_list),
            'message' => $smsContent
        ];

        //CURL
        $my_curl = curl_init();
        curl_setopt_array($my_curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
//        CURLOPT_MAXREDIRS => 10,
//        CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data_string),
            CURLOPT_HTTPHEADER => config('dwsync.curl.httpHeader'),
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_USERAGENT => config('dwsync.curl.userAgent'),
            CURLOPT_FOLLOWLOCATION => TRUE
        ));

        $status = curl_exec($my_curl);
        $errorNo = curl_errno($my_curl);
        $error = curl_error($my_curl);

        curl_close($my_curl);
        $result = [
            'status' => $status,
            'curl_error_no' => $errorNo,
            'curl_error' => $error
        ];

        //Save log per num
        $currentDate = date_format(new \DateTime(), "Y-m-d H:i:s");
        $s = json_decode($status, true);
        foreach (json_decode($status, true) as $currentNum => $currentStatus){
            $dwSms = new DwSms([
                'date' => $currentDate,
                'recipient' => $currentNum,
                'content' => $smsContent,
                'status' => $currentStatus,
                'curl_error_no' => $result['curl_error_no'],
                'curl_error' => $result['curl_error']
            ]);
            $dwSms->save();
        }
        return $result;
    }
}
