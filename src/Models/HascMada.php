<?php

namespace Hni\Dwsync\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\DB;

/**
 * Class DwQuestion
 * @package Hni\Dwsync\Models
 * @version September 21, 2017, 1:31 pm UTC
 *
 * @property \Hni\Dwsync\Models\DwProject dwProject
 * @property string xformQuestionId
 * @property string questionId
 * @property string path
 * @property string labelDefault
 * @property string labelFr
 * @property string labelUs
 * @property string dataType
 * @property string dataFormat
 * @property integer order
 * @property string linkedIdnr
 * @property string periodType
 * @property string periodTypeFormat
 * @property tinyInteger isUnique
 * @property tinyInteger isMigrated
 */
class HascMada extends Model
{
    public $table = 'hasc_mada';
    public $timestamps = false;

    public $fillable = [
        'region',
        'code_region',
        'district',
        'code_district',
        'commune',
        'code_commune',
        'fkt',
        'code_fkt',
        'village',
        'code_village',
        'csb',
        'epp',
        'college',
        'lycee',
        'date_submission'
    ];


    /**
     * @return object
     */
    public static function generateVillageCode($values, $arg1){
        $result = array();
        $result['value'] = array();
        $result['action'] = 'create';

        $where = '';
        $args = explode(',',$arg1);
        foreach ($args as $arg){
            $argContents = explode(':',$arg);
            $valueField = $argContents[0];
            $databaseField = $argContents[1];
            $splitStrings = preg_split('/[\ \_]+/', $values[$valueField]);
            foreach ($splitStrings as $str){
                $param = strtolower($str);
                $param = addslashes($param);
                $where.= " and LOWER($databaseField) LIKE '%$param%'";
            }
        }
        $sql = "SELECT *
                FROM hasc_mada
                WHERE 1=1
                $where
                ORDER BY code_village DESC 
                LIMIT 0,1;";
//        echo "</br>".$sql;
        $villages =
            DB::select($sql);

        foreach ($villages as $village) {
            $resultLine = array();
            $resultLine['region'] = $village->region;
            $resultLine['code_region'] = $village->code_region;
            $resultLine['district'] = $village->district;
            $resultLine['code_district'] = $village->code_district;
            $resultLine['commune'] = $village->commune;
            $resultLine['code_commune'] = $village->code_commune;
            $resultLine['fkt'] = $village->fkt;
            $resultLine['code_fkt'] = $village->code_fkt;
            if(empty($village->code_village) && empty($village->village)){
                $result['action'] = 'edit';
                $result['id'] = $village->id;
                $resultLine['code_village'] = $village->code_fkt.'01';
            }
            else{
                $last2Char = substr($village->code_village, -2);
                $intValue = intval($last2Char)+1;
                $StringValue = '';
                if($intValue < 10){
                    $StringValue .= '0'.$intValue;
                }
                else{
                    $StringValue .= $intValue;
                }
                $resultLine['code_village'] = $village->code_fkt.$StringValue;
            }
            $result['value'] = $resultLine;
            break;
        }
        return $result;
    }


}
