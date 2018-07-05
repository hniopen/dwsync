<?php
/**
 * Created by PhpStorm.
 * User: rs
 * Date: 25/09/2017
 * Time: 16:22
 */
$dwBaseUrl = empty(env('DW_BASE_URL'))?'https://app.datawinners.com':(env('DW_BASE_URL'));

return [

    /*
    |--------------------------------------------------------------------------
    | Helpers config
    |--------------------------------------------------------------------------
    |
    | Add here every config related to Datawinners
    |
    */
    'generator' => [
        'namespace' => 'Dwsubmissions',
        'namespace_for_extended' => 'DwExtended',
        'prefix' => [
            'submission' => 'DwSubmission',
            'submissionValue' => 'DwSubmissionValue',
            'dbSubValueView' => 'view_subvalue_',
            'stubSubmissionDB' => 'dw_submission',
        ],
        'jsonStubPath' => '/resources/model_schemas/'
    ],
    'defaultApiStartDate' => '01-01-2013',
    'url' => [
        'smsApi' => "https://[sms_account]@app.datawinners.com/sms",//do not use UAT SMS URL unless we need really to test SMS in UAT side
        'formList' => $dwBaseUrl.'/xforms/formList',
        'xform' => $dwBaseUrl.'/xforms/',
    ],
    'curl' => [
        'userAgent' => "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)",
        'httpHeader' => array(
            // Set here required headers
            "accept: */*",
            "accept-language: en-US,en;q=0.8",
            "content-type: application/json",
        ),
    ],
    'event' => [
        'sms' => [
            'boolSend' => env('BOOL_SEND_SMS', 'false'),
            'boolSendError' => env('BOOL_SEND_ERROR_SMS', 'false'),
            'adminNumbers' => env('ADMIN_NUMBERS', ''),//should be a list
            'testerNumbers' => env('TESTER_NUMBERS', ''),//should be a list
        ]
    ],
    'xlsform' => [
        'uploadPath' => 'storage/uploads/xlsforms'
    ],
    'account' => [
        'sms' => env('DW_SMS_ACCOUNT', 'false')
    ],
    'overrideViews' => false,//publish 'views' then turn it to true
    'dwBaseUrl' => $dwBaseUrl
];
