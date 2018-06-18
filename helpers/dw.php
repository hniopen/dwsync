<?php
/**
 * Created by PhpStorm.
 * User: rs
 * Date: 28/09/2017
 * Time: 12:11
 */

/**
 * Get question Id list from DW json response
 *
 * @param $tJson
 * @param $questionKey
 * @return array
 */
function fctGetQuestionsFromJson($tJson, $questionKey = 'values')
{
    $output = [];
    if ($tJson)
        foreach ($tJson as $item) {
            $output = array_unique(array_merge($output, array_unique(recursive_string_keys($item[$questionKey]))));
        }
    return $output;
}


/**
 * Get all children array key (leaf) from a given key (root)
 *
 * @param $input
 * @param null $search_value
 * @return array
 */
function recursive_string_keys($input, $search_value = null)
{
    $output = ($search_value !== null ? array_keys($input, $search_value) : array_keys($input));
    foreach ($input as $sub) {
        if (is_array($sub)) {
            if (is_numeric(key($sub)[0])) {
                $output = ($search_value !== null ? array_merge($output, recursive_string_keys($sub, $search_value)) : array_merge($output, recursive_string_keys($sub)));
            }
        }
    }
    return $output;
}

/**
 * Get question Id list from DW xform response
 *
 * @param $XformResult
 * @return array
 */
function fctGetQuestionsFromXform($XformResult)
{
    $output = [];
    $dom = new \DomDocument();
    $dom->preserveWhiteSpace = FALSE;
    $dom->loadXML($XformResult);

    //Initiate question values from 'data'
    $listeData = $dom->getElementsByTagName('data');
    $data = $listeData->item(0);

    foreach ($data->childNodes as $node) {
        if ($node->nodeName != 'eid' and $node->nodeName != 'form_code') {
            $output[$node->nodeName] = ['type' => '', 'label' => '', 'tag' => ''];
        }
    }

    //Set type from 'bind'
    $listeBind = $dom->getElementsByTagName('bind');
    foreach ($listeBind as $bind) {
        $vQuest = $bind->getAttribute('nodeset');
        $vQuest = substr($vQuest, 1);
        $vPos = strpos($vQuest, "/");
        $vQuest = substr($vQuest, $vPos + 1);
        if (array_key_exists($vQuest, $output)) {
            $output[$vQuest]['type'] = $bind->getAttribute('type');
        }

    }
    //Set Label & tag from 'body'
    $listeBody = $dom->getElementsByTagName('body');
    $bodyElement = $listeBody->item(0)->childNodes;
    foreach ($bodyElement as $elmt) {
        $vQuest = $elmt->getAttribute('ref');
        if (array_key_exists($vQuest, $output)) {
            $output[$vQuest]['tag'] = $elmt->nodeName;
            $output[$vQuest]['label'] = $elmt->firstChild->nodeValue;
        }
    }
    return $output;
}

/**
 * CURL initiation to invoke secure link between the app and DW:
 * - feed
 * - api
 *
 * @param $url
 * @param $credential
 * @param int $curlHttpAuth
 * @return array
 */
function fctInitCurlDw($url, $credential, $curlHttpAuth = CURLOPT_HTTPAUTH)
{
    $my_curl = curl_init();
    $tCred = explode(":", $credential);
    curl_setopt($my_curl, CURLOPT_URL, $url);
    curl_setopt($my_curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($my_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($my_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($my_curl, CURLOPT_FRESH_CONNECT, TRUE);
    curl_setopt($my_curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($my_curl, CURLOPT_USERAGENT, config('dwsync.userAgentCurl'));
    curl_setopt($my_curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($my_curl, CURLOPT_HTTPAUTH, $curlHttpAuth);
    curl_setopt($my_curl, CURLOPT_USERPWD, strtolower($tCred[0]) . ":" . $tCred[1]);//cred pattern : {mail}:{pwd}, force mail to lowercase
    curl_setopt($my_curl, CURLOPT_FOLLOWLOCATION, TRUE);
    $res = curl_exec($my_curl);
    $tResult = ['raw' => $res, 'json' => json_decode($res, true), 'code' => curl_errno($my_curl), 'msg' => curl_error($my_curl)];
    curl_close($my_curl);
    return $tResult;
}

/**
 * Get DB Data type from xform type. The purpose is to have a pre-formated type for DB generation
 *
 * @param $type
 * @return mixed|string
 */
function fctGetDBTypeFromXform($type)
{
    $dbType = ['int' => 'VARCHAR(20)', 'decimal' => 'VARCHAR(20)', 'string' => 'TEXT', 'select1' => "VARCHAR(20)", 'select2' => "VARCHAR(20)"];
    if (array_key_exists($type, $dbType))
        return $dbType[$type];
    else
        return fctGetDBTypeFromXls($type);
}

/**
 * Get DB Data type from xls column name. The purpose is to have a pre-formated type for DB generation
 *
 * @param $type
 * @return mixed|string
 */
function fctGetDBTypeFromXls($type)
{
    $validXlsformDataType = array('text' => "TEXT",
        'dw_idnr' => "VARCHAR(20)",
        'select_one' => "VARCHAR(100)",
        'select_multiple' => "TEXT",
        'cascading_select' => "TEXT",
        'geopoint' => "VARCHAR(100)",
        'integer' => 'VARCHAR(20)',
        'decimal' => "VARCHAR(20)",
        'date' => "VARCHAR(20)",
        'time' => "VARCHAR(20)",
        'barcode' => "TEXT",
        'calculate' => "TEXT",
        'note' => "TEXT");
    if (array_key_exists($type, $validXlsformDataType))
        return $validXlsformDataType[$type];
    else
        return 'TEXT';
}

/**
 * DW returns multiple format of date (with different delimiter)
 * In the app, we would like to uniform them with YYYY{delimiter}MM{delimiter}DD
 *
 * @param $dateValue
 * @param $periodType
 * @param $periodTypeFormat
 * @param string $newDelimiter
 * @return string
 */
function fctReformatDateToYearMonthDay($dateValue, $periodType, $periodTypeFormat, $newDelimiter = "-")
{
    if ($dateValue) {
        switch ($periodType) {
            //case : d, m, y, ym, ymd
            case "ym":
                if ($periodTypeFormat == "MM.YYYY")
                    $delimiter = ".";
                elseif ($periodTypeFormat == "MM-YYYY")
                    $delimiter = "-";
                elseif ($periodTypeFormat == "MM/YYYY")
                    $delimiter = "/";
                else//pattern not found
                    return $dateValue;
            
                $d =  explode($delimiter, $dateValue);

                try{
                    $month = fctForceToDoubleDigital($d[0]);
                    $year = $d[1];
                    return $year.$newDelimiter.$month;
                }catch (Exception $e){//error delimiter from seder
                    return $dateValue;
                }
                break;
            case "ymd":
                if ($periodTypeFormat == "DD.MM.YYYY")
                    $delimiter = ".";
                elseif ($periodTypeFormat == "DD-MM-YYYY")
                    $delimiter = "-";
                elseif ($periodTypeFormat == "DD/MM/YYYY")
                    $delimiter = "/";
                else//pattern not found
                    return $dateValue;
            
                $d =  explode($delimiter, $dateValue);

                try{
                    $day = fctForceToDoubleDigital($d[0]);
                    $month = fctForceToDoubleDigital($d[1]);
                    $year = $d[2];
                    return $year.$newDelimiter.$month.$newDelimiter.$day;
                }catch (Exception $e){//error delimiter from seder
                    return $dateValue;
                }
                break;
            case "d":
            case "m":
            case "y":
            default:
                return $dateValue;
                break;
        }
    }
    return $dateValue;
}

function fctForceToDoubleDigital($month){
    $number = $month * 1;
    if($number <= 9)
        return "0".$number;
    else
        return $number;
}

