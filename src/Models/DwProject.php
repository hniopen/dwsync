<?php

namespace Hni\Dwsync\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use File;
use Excel;
use Illuminate\Support\Facades\DB;

/**
 * Class DwProject
 * @package Hni\Dwsync\Models
 * @version September 20, 2017, 11:02 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection DwQuestion
 * @property \Hni\Dwsync\Models\DwEntityType dwEntityType
 * @property string questCode
 * @property string submissionTable
 * @property integer parentId
 * @property string comment
 * @property tinyInteger isDisplayed
 * @property string xformUrl
 * @property string credential
 * @property string entityType
 * @property string formType
 * @property string longQuestCode
 * @property tinyInteger autoSync
 */
class DwProject extends Model
{
//    use SoftDeletes;

    public $table = 'dw_projects';
    public $timestamps = false;

    protected $dates = ['deleted_at'];
    //array of dwQuestions - not yet saved
    private $questionsToAdd = [];
    //array of columns to add in the data table for arbitrarily repeated data - not yet saved
    private $columnsToAdd = [];
    private $submissionClassName, $submissionClass;
    private $tAllQuestions = [], $tCheckingResult = [];
    private $beginTagType = array('begin_group', 'begin_repeat');
    private $endTagType = array('end_group', 'end_repeat');
    private $validMimeType = array('application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    protected $file, $startDate, $endDate;
    const JSON_FEED_DATA_KEY = 'values';
    const JSON_API_DATA_KEY = 'submission_data';
    //protected $appends = [];//custom attributes
    public $fillable = [
        'questCode',
        'submissionTable',
        'parentId',
        'comment',
        'isDisplayed',
        'xformUrl',
        'credential',
        'entityType',
        'formType',
        'longQuestCode',
        'autoSync'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'questCode' => 'string',
        'submissionTable' => 'string',
        'parentId' => 'integer',
        'comment' => 'string',
        'xformUrl' => 'string',
        'credential' => 'string',
        'entityType' => 'string',
        'formType' => 'string',
        'longQuestCode' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'questCode' => 'required',
        'submissionTable' => 'nullable',
        'parentId' => 'nullable:numeric',
        'comment' => 'nullable',
        'isDisplayed' => 'min:0|max:1',
        'xformUrl' => 'nullable',
        'credential' => 'required',
        'entityType' => 'required',
        'formType' => 'required',
        'longQuestCode' => 'nullable',
        'autoSync' => 'min:0|max:1',
    ];

    public function __construct($attributes = array())
    {
        parent::__construct($attributes); // Eloquent
        $this->initAbstractAttributes();
    }

    public function initAbstractAttributes()
    {//do not save in DB
        $postFix = $this->questCode;
        $this->submissionClassName = config('dwsync.generator.prefix.submission') . $postFix;
        if(class_exists("App\Models\\" . config('dwsync.generator.namespace_for_extended') . "\\" . $this->submissionClassName)) {
            //make sure your extended model was created by php artisan make:model
            $this->submissionClass = "App\Models\\" . config('dwsync.generator.namespace_for_extended') . "\\" . $this->submissionClassName;
        }else{
            $this->submissionClass = "App\Models\\" . config('dwsync.generator.namespace') . "\\" . $this->submissionClassName;
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            //Delete questions first
            $model->dwQuestions()->delete();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function dwQuestions()
    {
        return $this->hasMany(\Hni\Dwsync\Models\DwQuestion::class, 'projectId', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dwEntityType()
    {
        return $this->belongsTo(\Hni\Dwsync\Models\DwEntityType::class, 'entityType', 'type');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function dwSubmissions()
    {
        $postFix = $this->questCode;
        $selectedClass = "App\Models\\" . config('dwsync.generator.namespace') . "\\" . config('dwsync.generator.prefix.submission') . $postFix;
        return $this->hasMany($selectedClass, 'projectId', 'id');
    }

    public function refreshLastSubmission()
    {
        $lastSub = $this->dwSubmissions()->latest('dwSubmittedAt')->first();
        $this->lastSubmission = $lastSub;
    }

    /**
     * Get the LastSubmissionDate.
     *
     * @return mixed
     */
    public function getLastSubmission()
    {
        if (!$this->lastSubmission)
            $this->refreshLastSubmission();
        return $this->lastSubmission;
    }

    /**
     * @return mixed
     */
    public function getSubmissionClassName()
    {
        if(!Str::contains($this->submissionClassName, $this->questCode))
            $this->initAbstractAttributes();
        return $this->submissionClassName;
    }

    /**
     * @return mixed
     */
    public function getSubmissionClass()
    {
        if(!Str::contains($this->submissionClass, $this->questCode))
            $this->initAbstractAttributes();
        return $this->submissionClass;
    }

    public function getTableName(){
        if(!Str::contains($this->submissionClassName, $this->questCode))
            $this->initAbstractAttributes();
        return Str::snake(str_plural($this->submissionClassName));
    }

    public function checkQuestionsFromDwSubmissions()
    {
        $url = config('dwsync.dwBaseUrl').$this->dwEntityType->apiUrl . $this->questCode;
        $vCredential = fctReversibleDecrypt($this->credential);
        if ($this->entityType == 'Q') {
            if ($this->startDate) {
                $start=date('d-m-Y H:i:s',strtotime($this->startDate));
                //$this->startDate = date_create_from_format('Y-m-d H:i:s', $lastSubmission->dwSubmittedAt);
                $this->startDate =$start;
            } else {
                $this->startDate = config('dwsync.defaultApiStartDate') . " 0:0:0";
            }

            $this->endDate = date('d-m-Y H:i:s', strtotime($this->endDate . "+2 days"));
            $url = $url . "?start_date=" . $this->startDate . "&end_date=" . $this->endDate;
            $this->tCheckingResult = fctInitCurlDw($url, $vCredential, CURLAUTH_BASIC);//CURLAUTH_BASIC
        }
        else {
            $this->startDate = date('d-m-Y', strtotime($this->startDate ));
            $this->endDate = date('d-m-Y', strtotime($this->endDate . "+2 days"));
            $url = $url . "/$this->startDate/$this->endDate";
            $this->tCheckingResult = fctInitCurlDw($url, $vCredential);//CURLAUTH_DIGEST
        }

        $tMessage = ['statusCode' => $this->tCheckingResult['code'], 'text' => $this->tCheckingResult['msg'] . " " . $url];

        //Get all questions
        $this->tAllQuestions = [];
        if ($tMessage['statusCode'] == 0) {
            if ($this->entityType == 'Q')
                $tAllQuestions = fctGetQuestionsFromJson($this->tCheckingResult['json']);
            else
                $tAllQuestions = fctGetQuestionsFromJson($this->tCheckingResult['json']['submissions'], DwProject::JSON_API_DATA_KEY);
        }
        return ['result' => $this->tCheckingResult['json'], 'message' => $tMessage, 'questions' => $tAllQuestions];
    }

    public function checkQuestionsFromDwXform()
    {
        $url = config('dwsync.url.xform') . $this->longQuestCode;
        $vCredential = fctReversibleDecrypt($this->credential);
        $this->tCheckingResult = fctInitCurlDw($url, $vCredential);//CURLAUTH_DIGEST
        $this->tAllQuestions = [];
        $tMessage = ['statusCode' => $this->tCheckingResult['code'], 'text' => $this->tCheckingResult['msg'] . " " . $url];
        if ($tMessage['statusCode'] == 0) {
            $tAllQuestions = fctGetQuestionsFromXform($this->tCheckingResult['raw']);
        }
        return ['result' => $this->tCheckingResult['raw'], 'message' => $tMessage, 'questions' => $tAllQuestions];
    }

    private function generateQuestionIdFromPath($vPath, $vName, $in_repeat = false){
        $separator = "__";//double _
        if($vPath && $in_repeat)
            return str_replace("/", $separator, $vPath).$separator.'1__'.$vName;//if we're in a repeat then create the first question. More will be added if more are synced.
        elseif($vPath) {
            return str_replace("/", $separator, $vPath).$separator.$vName;//there may be duplicates name in xlsform. Pattern = group1__group2__...groupN__name
        }
        else
            return $vName;
    }
    public function checkQuestionsFromDwXls($file)
    {
        $this->file = $file;
        $this->tCheckingResult = [];
        $this->tAllQuestions = [];
        $filePath = $file->getRealPath() . "/" . $file->getClientOriginalName();
        $fileType = $file->getMimeType();
//        $ext = $file->getClientOriginalExtension();
        //$file->getSize();

        $tMessage = ['statusCode' => 0, 'text' => $filePath];
        if (!in_array($fileType, $this->validMimeType)) {
            $tMessage['statusCode'] = 1;
            $tMessage['text'] = "Wrong excel file mime type : " . $fileType;
            abort(403, $tMessage['text']);
        }
        if ($tMessage['statusCode'] == 0) {
            $survey = Excel::selectSheets('survey')->load($file)->get();
            $this->tCheckingResult = $survey;
            $vFinTag = "";
            $vPath = "";
            $vCurrentPath = "";
            $vOrder = 1;
            $output = [];
            $in_repeat = false;
            foreach ($survey as $rowItem) {
                if ($rowItem['type'] && $rowItem['type'] != "") {//not a blank line
                    //Existing values from Cells
                    $tType = explode(" ", $rowItem['type']);
                    if ($tType[0] == "begin" || ($tType[0] == "end" && isset($tType[1])))//uniformisation : "end group", "end_group", ...
                        $vType = $tType[0] . "_" . $tType[1];
                    elseif ($tType[0] == "cascading_select") {
                        $vType = "cascading_select";
                    } else
                        $vType = $rowItem['type'];

                    if (!in_array($vType, $this->endTagType)) {//"end ..." tags contain "null" name
                        $vName = $rowItem['name'];
                        $questId = $this->generateQuestionIdFromPath($vPath, $vName, $in_repeat);
                        $vLabel = $rowItem['label'];
                        $vRelevant = isset($rowItem['relevant']) ? $rowItem['relevant'] : null;
                        $vRequired = isset($rowItem['required']) ? $rowItem['required'] : null;
                        $vHint = isset($rowItem['hint']) ? $rowItem['hint'] : null;
                        $vCalculation = isset($rowItem['calculation']) ? $rowItem['calculation'] : null;
                        $vConstraint = isset($rowItem['constraint']) ? $rowItem['constraint'] : null;
                        $vConstraintMessage = isset($rowItem['constraint_message']) ? $rowItem['constraint_message'] : null;

                        //do not deal with valid data type : add everything
                        if($vPath && $in_repeat) {
                            $output[$questId]['name'] = '1__'.$vName;
                        } else {
                            $output[$questId]['name'] = $vName;
                        }
                        $output[$questId]['type'] = $vType;
                        $output[$questId]['label'] = $vLabel;
                        $output[$questId]['order'] = $vOrder++;
                        $output[$questId]['path'] = $vPath;
                        if ($vType == "cascading_select") {//add cascade values : Loop from Cascades
                            $cascade = Excel::selectSheets("cascades")->load($file)->get();
                            $cascadeHeader = $this->getCascadeColumnNames();
                            foreach ($cascade as $cascadeRow) {
                                foreach ($cascadeHeader as $currentHeadValue) {
                                    if ($currentHeadValue != "name") {
                                        //name
                                        if ($currentHeadValue == $cascadeHeader[count($cascadeHeader) - 1]) {//last header
                                            $vCurrentName = $vName;//without postfix
                                        } else {
                                            $vCurrentName = $vName . "_" . $currentHeadValue;
                                        }
                                        $currentQuestId = $this->generateQuestionIdFromPath($vPath, $vCurrentName);
                                        //label
                                        if ($cascadeRow['name'] == "label") {//has label in 2nd row
                                            $vCurrentLabel = $cascadeRow[$currentHeadValue];
                                        } else {
                                            $vCurrentLabel = $currentHeadValue;
                                        }

                                        $output[$currentQuestId]['name'] = $vCurrentName;
                                        $output[$currentQuestId]['type'] = "cascading_value";
                                        $output[$currentQuestId]['label'] = $vCurrentLabel;
                                        $output[$currentQuestId]['order'] = $vOrder++;
                                        $output[$currentQuestId]['path'] = $vPath;
                                    }
                                }
                                break;
                            }
                        }

                        //Deal with groups & repeat
                        if (in_array($vType, $this->beginTagType)) {
                            if($vType == 'begin_repeat') {
                                $in_repeat = true;
                            }
                            $vCurrentPath = $vName;
                            if (strlen($vPath) > 0)
                                $vPath = $vPath . "/";
                            $vPath = $vPath . $vCurrentPath;
                        }
                    } else {//end tag
                        if($vType == 'end_repeat') {
                            $in_repeat = false;
                        }
                        if (strlen($vPath) > strlen($vCurrentPath)) {
                            $vPath = substr($vPath, 0, strlen($vPath) - strlen($vCurrentPath) - 1);
                            $tPath = explode("/", $vPath);

                            $vCurrentPath = $tPath[count($tPath) - 1];
                        } else {
                            $vPath = "";
                            $vCurrentPath = "";
                        }
                    }
                }
            }
            $this->tAllQuestions = $output;
//            });
        }
        return ['result' => $this->tCheckingResult, 'message' => $tMessage, 'questions' => $this->tAllQuestions];
    }

    /**
     * Add question without loosing data in the table
     * */
    public function addQuestionsToModel($questions){
        $sql = "";
        $table = $this->getTableName();
        $result = false;
        foreach ($questions as $item => $t){
            if (strpos($item, '@template') !== false)
                continue;
            if(Schema::hasColumn($table, $item))
                continue;
            if($sql != "")
                $sql .= ",";
            $sql .= "ADD $item ".$t['type'];
        }
        if($sql != ""){
            $result = DB::statement("ALTER TABLE $table ".$sql);
            $this->generateSubmissionModelFromTable("update");
        }
        return $result;
    }

    /**
     * Add question without loosing data in the table
     * */
    public function removeQuestionsInModel($questions){
        $sql = "";
        $table = $this->getTableName();
        $result = false;
        foreach ($questions as $item => $t){
            if($sql != "")
                $sql .= ",";
            $sql .= "DROP COLUMN $item";
        }
        if($sql != ""){
            $result = DB::statement("ALTER TABLE $table ".$sql);
            $this->generateSubmissionModelFromTable("update");
        }
        return $result;
    }

    private function getCascadeColumnNames()
    {
        $trueHead = [];
        $allHeads = Excel::selectSheets("cascades")->load($this->file)->first()->keys()->toArray();
        foreach ($allHeads as $head) {
            if (strlen($head) > 0 && !is_numeric($head))
                $trueHead[] = $head;
        }
        return $trueHead;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        if($startDate)
            $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    public function sync()
    {
        set_time_limit(0);//forever
        ini_set('memory_limit','512M');
        DB::disableQueryLog();
        $url = config('dwsync.dwBaseUrl').$this->dwEntityType->apiUrl . $this->questCode;
        //TODO: check last submission datetime
        $this->refreshLastSubmission();//force refresh
        $lastSubmission = $this->getLastSubmission();

        $vCredential = fctReversibleDecrypt($this->credential);
        if ($this->entityType == 'Q') {
            if(!$this->startDate){
                if ($lastSubmission) {
                    $this->startDate = date_create_from_format('Y-m-d H:i:s', $lastSubmission->dwSubmittedAt);
                    $this->startDate = date_format($this->startDate, "d-m-Y H:i:s");
                } else {
                    $this->startDate = config('dwsync.defaultApiStartDate') . " 0:0:0";
                }
            }
            $endDate = date('d-m-Y H:i:s', strtotime(date('Y-m-d') . "+2 days"));
            $url = $url . "?start_date=" . $this->startDate . "&end_date=" . $endDate;
            $this->tCheckingResult = fctInitCurlDw($url, $vCredential, CURLAUTH_BASIC);//CURLAUTH_BASIC
        } else {
            //Don't check last submission : to consider 'deleted'
            $this->startDate = config('dwsync.defaultApiStartDate');
            $endDate = date('d-m-Y', strtotime(date('Y-m-d') . "+2 days"));
            $url = $url . "/$this->startDate/$endDate";
            $this->tCheckingResult = fctInitCurlDw($url, $vCredential);//CURLAUTH_DIGEST
        }

        $tMessage = ['statusCode' => $this->tCheckingResult['code'], 'text' => $this->tCheckingResult['msg'] . " " . $url];

        //Pull all submissions
        $tAllSubmissions = [];
        if ($tMessage['statusCode'] == 0) {
            if ($this->entityType == 'Q') {
                $tAllSubmissions = $this->pullFeedData($this->tCheckingResult['json']);
            } else {
                $tAllSubmissions = $this->pullApiData($this->tCheckingResult['json']['submissions']);
            }

        }
        return ['result' => $this->tCheckingResult['json'], 'message' => $tMessage, 'submissions' => $tAllSubmissions];
    }

    private function pullFeedData($jsonResult)
    {
        set_time_limit(0);//forever
        ini_set('memory_limit','512M');
        DB::disableQueryLog();
        $output = [];
        $tStatus = ['success' => 0, 'error' => 0, 'deleted' => 0, 'updated' => 0, 'inserted' => 0, 'wrong_idnr' => 0];
        $uniqueQuestionsForDuplicates = $this->getUniqueQuestions()->get()->toArray();
        foreach ($jsonResult as $item) {
            //Datas
            $status = $item['status'];//success, deleted, error, ...
            $dataSenderId = $item['data_sender_id'];//code or phone number (if not registered)
            $submissionModifiedTime = $item['submission_modified_time'];//2015-11-02 10:19:51.867840+00:00
            $feedModifiedTime = $item['feed_modified_time'];//2015-11-02 10:19:51.899741+00:00
            $submissionId = $item['id'];//410dcfe2814b11e5a2f712313d2da6d0
            $tQuestions = $item[DwProject::JSON_FEED_DATA_KEY];//{"q3":"john smith","q2":"52"}

            $t = explode(".", $feedModifiedTime);
            //Insert submissions
            $uniqueColumns = ['submissionId' => $submissionId];
            $currentSubmission = $this->submissionClass::firstOrNew($uniqueColumns);
            $currentSubmission->projectId = $this->id;
            $currentSubmission->status = $status;
            $currentSubmission->datasenderId = $dataSenderId;
            //Check error status ###### Fire event
            $_isValid = 0;
            if ($status == "deleted") {
                //DELETED ###### Fire event
                //TODO : call external event definition
            } elseif ($status == "error") {
                //ERROR ###### Fire event
                //TODO : call external event definition
            } elseif ($status == "success") {
                $_isValid = 1;
                //Success ####### Fire event
                //TODO : call external event definition

                //Check valid DS (doesn't exist in DS list) ##### Fire event
                //TODO : then add this $tStatus['wrong_idnr']++;
                //TODO : call external event definition
            } else {//unexpected status
                //TODO : Fire event for admin
                if (isset($tStatus[$status]))
                    $tStatus[$status]++;
                else
                    $tStatus[$status] = 1;
            }

            if ($currentSubmission->id) {//already exist = update
                $currentSubmission->dwUpdatedAt = $t[0];
                $currentSubmission->dwUpdatedAt_u = $t[1];
                $tStatus['updated']++;
                if ($status == "success") {
                    //Check modified data : same $submissionId #### Fire event
                    //TODO : call external event definition
                }
            } else {//insert
                $tStatus['inserted']++;
                $currentSubmission->dwSubmittedAt = $t[0];
                $currentSubmission->dwSubmittedAt_u = $t[1];
            }
            if (!array_key_exists($status, $tStatus))//in case there is new status from DW
                $tStatus[$status] = 1;
            else
                $tStatus[$status]++;
            $currentSubmission->isValid = $_isValid;

            //Insert values
            $this->saveFeedValuesFromJson($currentSubmission, $tQuestions);

            //unvalidate duplicates
            $this->updateDuplicatesOf($currentSubmission, $uniqueQuestionsForDuplicates);

            //insert or update
            $currentSubmission->save();
            $output[] = $currentSubmission;
        }
        DB::connection()->enableQueryLog();
        return ['data' => $output, 'status' => $tStatus];
    }

    public function fetchAndBuildQuestionFromData($item, $value) {
        $uniqueColumns = ['projectId'=>$this->id,'questionId'=>$item];
        $dwQuestion = DwProject::getQuestionForColumns($uniqueColumns);
        $dwQuestion->xformQuestionId = $value['name'];
        $dwQuestion->labelDefault = $value['label'];
        $dwQuestion->dataType = $value['type'];
        //$dwQuestion->order = $value['order'];
        $dwQuestion->path = $value['path'];
        return $dwQuestion;
    }


    public function addRepeatQuestionsFromDataSync() {
        $insertCount = 0;
        foreach ($this->questionsToAdd as $currentDwQuestion){
            if (strpos($currentDwQuestion->questionId, '@template') !== false)
                continue;
            if(!$currentDwQuestion->id)
                $insertCount++;
            $currentDwQuestion->save();
        }
        if($insertCount > 0){
            //where we add the new column to the project data table
            $this->addQuestionsToModel($this->columnsToAdd);
        }
        $this->questionsToAdd = [];
        $this->columnsToAdd = [];
    }
    public static function getQuestionForColumns($uniqueColumns) {
        return DwQuestion::firstOrNew($uniqueColumns);
    }

    //not super efficient but it works
    //Relies on begin_repeats added later to be ordered within that group
    //Only affects questions with begin_repeat paths - not begin_group
    public static function reorderQuestionsForBeginRepeat($questions) {
        $intermediateArray = [];
        $reorderedQuestions = [];
        foreach($questions as $question) {
            $intermediateArray[$question->questionId] = $question;
        }
        foreach($questions as $question) {
            //Only affects questions with begin_repeat paths - not begin_group
            if(array_key_exists($question->path, $intermediateArray) && $intermediateArray[$question->path] && (is_array($intermediateArray[$question->path]) || $intermediateArray[$question->path]->dataType == "begin_repeat")) {
                if(is_array($intermediateArray[$question->path])) {
                    $intermediateArray[$question->path][] = $question;
                } else {
                    $intermediateArray[$question->path] = [
                        $intermediateArray[$question->path],
                        $question
                    ];
                }
                $intermediateArray[$question->questionId] = NULL;
            }
        }
        foreach($intermediateArray as $questionId => $question) {
            if(is_array($question)) {
                foreach($question as $subQuestion) {
                    $newOrder = count($reorderedQuestions) + 1;
                    $subQuestion->order = $newOrder;
                    $reorderedQuestions[] = $subQuestion;
                }
            } else {
                if($question) {
                    $newOrder = count($reorderedQuestions) + 1;
                    $question->order = $newOrder;
                    $reorderedQuestions[] = $question;
                }
            }
        }
        return $reorderedQuestions;
    }

    //It's not actually much quicker to only load the existing questions, merge with the new begin repeat questions and reorder then save
    public function reorderQuestions() {
        $reorderedQuestions = DwProject::reorderQuestionsForBeginRepeat($this->dwQuestions()->get());
        foreach($reorderedQuestions as $question) {
            $question->save();
        }
    }

    public function getNewQuestionsFromRepeatData($value, $xformQuestionId) {
        //$value = array ( 0 => array ( 'person_name' => 'Precous kapininga', 'person_age' => '27', 'chronic_type' => array ( ), 'disabilities' => 'no', 'govt_programes' => array ( 0 => 'Irrigation', 1 => 'Input revolving scheme', ), 'disabilities_kind' => array ( ), 'chronic' => 'no', 'relation' => 'Child of head of household', 'fit_work' => 'yes', 'disab_support' => '', 'orphan_status' => array ( ), 'education' => 'Form 3-4', 'govt_programes_yes_no' => 'yes', 'person_gender' => 'Male', ))
        $questionsToAdd = [];
        foreach($value as $i => $record) {
            //skip as 1 gets's created when questions are imported normally
            if($i+1 == 1) {
                continue;
            }
            foreach($record as $key => $value) {
                $uniqueColumns = ['projectId' => $this->id, 'questionId' => $xformQuestionId."__1__".$key];
                $relatedDwQuestion = DwProject::getQuestionForColumns($uniqueColumns);
                $questionId = $xformQuestionId.'__'.($i+1).'__'.$key;
                $question = [
                    'name' => ($i+1)."__".$key,
                    //hard to do from data - 
                    'type' => $relatedDwQuestion->type,
                    'label' => $relatedDwQuestion->label,
                    //used for what?
                    //'order' => 0,
                    'path' => $xformQuestionId
                ];
                $questionsToAdd[] = $this->fetchAndBuildQuestionFromData($questionId, $question);
            }
        }
        return $questionsToAdd;
    }
    public function getNewColumnsFromQuestionsToAdd($questionsToAdd) {
        $columnsToAdd = [];
        foreach($questionsToAdd as $question) {
            $columnsToAdd[$question->questionId] = ['type'=>fctGetDBTypeFromXls($question->type)];
        }
        return $columnsToAdd;
    }

    private function saveFeedValuesFromJson($currentSubmission, $tQuestions, $path = null)
    {
        set_time_limit(0);//forever
        ini_set('memory_limit','512M');
        DB::disableQueryLog();
        //if you modify this, please make sure to update saveApiValuesFromJson( ) too if needed
        foreach ($tQuestions as $xformQuestionId => $value) {
            //Get related question:
            $questionId = $this->generateQuestionIdFromPath($path, $xformQuestionId);
            $uniqueColumns = ['projectId' => $this->id, 'questionId' => $questionId];
            $relatedDwQuestion = DwQuestion::firstOrNew($uniqueColumns);
            if ($relatedDwQuestion->id) {//The question exists
                if ($relatedDwQuestion->dataType == "begin_group") {
                    if (is_array($value)) {
                        if($path)
                            $currentPath = $path."/".$xformQuestionId;
                        else
                            $currentPath = $xformQuestionId;
                        $this->saveFeedValuesFromJson($currentSubmission, $value[0], $currentPath);
                    } else {
                        //probably skipped value
                    }
                } elseif ($relatedDwQuestion->dataType == "begin_repeat") {
                    $this->questionsToAdd = $this->getNewQuestionsFromRepeatData($value, $xformQuestionId);
                    $this->columnsToAdd = $this->getNewColumnsFromQuestionsToAdd($this->questionsToAdd);
                    $this->addRepeatQuestionsFromDataSync();
                    $this->reorderQuestions();
                    if (is_array($value)) {
                        foreach ($value as $key => $repeatValue) {//key is numeric (incremental) : 0,1,2,...
                            $this->saveFeedValuesFromJson($currentSubmission, $repeatValue, $xformQuestionId."__".($key+1));
                        }
                    } else {
                        //probably skipped value
                    }
                } else {//Values
                    //TODO : deal with advanced Q repeat, idea > should add path in $uniqueColumns

                    if (is_array($value))//single or multiple choice
                        $currentValue = implode(",", $value);
                    else
                        $currentValue = $value;

                    if($currentSubmission->isValid == 0){//error
                        if(strlen($currentValue) > 20)
                            $currentValue = substr($currentValue, 0, 20);//Avoid insert error if data > VARCHAR(20)
                    }else{//success
                        if($relatedDwQuestion->dataType == "date")
                            $currentValue = fctReformatDateToYearMonthDay($currentValue, $relatedDwQuestion->periodType, $relatedDwQuestion->periodTypeFormat);
                    }

                    //Check if linked_idnr value in idnr list (IDNR exists) ##### Fire event
                    //TODO : call external event definition

                    //set value
                    $currentSubmission->$questionId = $currentValue;

                }
            } else {
                //The question doesn't exist yet
                //TODO : notify Admin in result, log
            }
        }
        DB::connection()->enableQueryLog();
    }

    private function pullApiData($jsonResult)
    {
        set_time_limit(0);//forever
        ini_set('memory_limit','512M');
        DB::disableQueryLog();
        $output = [];
        $tStatus = ['active' => 0, 'error' => 0, 'deleted' => 0, 'updated' => 0, 'inserted' => 0];
        foreach ($jsonResult as $item) {
            //Datas
            $status = $item['status'];//active, deleted, ...
            $submission_time = $item['submission_time'];//2014-12-01 07:16:06.232944+00:00
            $tQuestions = $item[DwProject::JSON_API_DATA_KEY];//{"status": "active","submission_data": {"geo_code": [  0,  0],"name": "Rasolofo","entity_type":[  "reporter"],"short_code": "rep8","location": [  "Ampefiloha",  "Madagascar"],"mobile_number": "261338471531"}
            $submissionId = $item[DwProject::JSON_API_DATA_KEY]['short_code'];//rep14
            $t = explode(".", $submission_time);
            //Insert submissions
            $uniqueColumns = ['submissionId' => $submissionId];
            $currentSubmission = $this->submissionClass::firstOrNew($uniqueColumns);
            $currentSubmission->projectId = $this->id;
            $currentSubmission->status = $status;
            //Check error status ###### Fire event
            $_isValid = 0;
            if ($status == "deleted") {
                //DELETED ###### Fire event
                //TODO : call external event definition
            } elseif ($status == "error") {
                //ERROR ###### Fire event
                //TODO : call external event definition
            } elseif ($status == "active") {
                $_isValid = 1;
                //Success ####### Fire event
                //TODO : call external event definition

                //Check valid DS (doesn't exist in DS list) ##### Fire event
                //TODO : then add this $tStatus['wrong_idnr']++;
                //TODO : call external event definition
            } else {//unexpected status
                //TODO : Fire event for admin
                if (isset($tStatus[$status]))
                    $tStatus[$status]++;
                else
                    $tStatus[$status] = 1;
            }

            if ($currentSubmission->id) {//already exist = update
                $currentSubmission->dwUpdatedAt = $t[0];
                $currentSubmission->dwUpdatedAt_u = $t[1];
                $tStatus['updated']++;
                if ($status == "active") {
                    //Check modified data : same $submissionId #### Fire event
                    //TODO : call external event definition
                }
            } else {//insert
                $tStatus['inserted']++;
                $currentSubmission->dwSubmittedAt = $t[0];
                $currentSubmission->dwSubmittedAt_u = $t[1];
            }

            if (!array_key_exists($status, $tStatus))//in case there is new status from DW
                $tStatus[$status] = 1;
            else
                $tStatus[$status]++;
            $currentSubmission->isValid = $_isValid;

            //Insert values
            $this->saveApiValuesFromJson($currentSubmission, $tQuestions);

            //insert or update
            $currentSubmission->save();
            $output[] = $currentSubmission;
        }
        return ['data' => $output, 'status' => $tStatus];
    }

    private function saveApiValuesFromJson($currentSubmission, $tQuestions)
    {
        set_time_limit(0);//forever
        ini_set('memory_limit','512M');
        DB::disableQueryLog();
        //if you modify this, please make sure to update saveFeedValuesFromJson( ) too if needed
        foreach ($tQuestions as $questionId => $value) {
            //Get related question:
            $uniqueColumns = ['projectId' => $this->id, 'questionId' => $questionId];
            $relatedDwQuestion = DwQuestion::firstOrNew($uniqueColumns);
            if ($relatedDwQuestion->id) {//The question exists
                //no repeat for API
                if (is_array($value))//single or multiple choice
                    $currentValue = implode(",", $value);
                else
                    $currentValue = $value;
                //set value
                $currentSubmission->$questionId = $currentValue;
            } else {
                //The question doesn't exist yet
                //TODO : notify Admin in result, log
            }
        }
    }

    public function generateSubmissionModels($operation = "create")
    {
        if ($operation == "create") {//before generation
            $this->cleanDirtyGeneratedFile($operation);
        }
        $this->generateSubmissionModelFromTable($operation);
        if ($operation == "rollback") {//after deletion
            $this->cleanDirtyGeneratedFile($operation);
        }
    }

    public function generateSubmissionExtendedModels($operation = "create")
    {
        //TODO: make generate submission automated (php artisan {cmd to create models from custom stubs})
//        if ($operation == "create") {//before generation
//            Artisan::call();
//        }
//        $this->generateSubmissionModelFromTable($operation);
//        if ($operation == "rollback") {//after deletion
//            $this->cleanDirtyGeneratedFile($operation);
//        }
    }

    private function generateSubmissionModelFromJson($operation = "create")
    {
        $result = [];
        $this->setInfyomConfig($operation);
        if ($operation == "create") {
            $stubFile = config('dwsync.generator.prefix.submission') . '_x.json';
            $submissionJsonText = file_get_contents(base_path() . config('dwsync.generator.jsonStubPath') . $stubFile);
            $submissionJsonText = str_replace('$[relatedModelName]$', $this->submissionValueClassName, $submissionJsonText);
            $submissionJsonArray = json_decode($submissionJsonText, true);
            $submissionJsonArray['prefix'] = strtolower(config('dwsync.generator.namespace'));
            $submissionJsonArray['modelName'] = $this->submissionClassName;
            $submissionOptions = [
                'model' => $this->submissionClassName,
                "--datatables" => 'true',
                "--skip" => "api_controller,api_routes,tests",
                "--views" => "index,show",
                "--prefix" => strtolower(config('dwsync.generator.namespace')),
                '--jsonFromGUI' => json_encode($submissionJsonArray)
            ];
            $result['code'] = Artisan::call('infyom:api_scaffold', $submissionOptions);
        } else {//rollback
            $submissionOptions = [
                "model" => $this->submissionClassName,
                "type" => "api_scaffold",
                "--prefix" => strtolower(config('dwsync.generator.namespace'))
            ];
            $result['code'] = Artisan::call('infyom:rollback', $submissionOptions);
        }
        $result['output'] = Artisan::output();
        return $result;
    }

    private function generateSubmissionModelFromTable($operation = "create"){
        $result = [];
        $this->setInfyomConfig($operation);

        if ($operation == "create" or $operation == "update") {
            //Clone DB
            $stubDB = config('dwsync.generator.prefix.stubSubmissionDB');
            $tableSubmission = $this->getTableName();
            if($operation == "create"){
                $sqlClone = "CREATE TABLE $tableSubmission LIKE $stubDB";
                $sqlResult = DB::statement($sqlClone);
                $toSkip = "api_controller,api_routes,tests";
            }else{//update
                $toSkip = "api_controller,api_routes,tests,menu,routes,scaffold_routes";
            }
            //Create or overwrite from table
            $submissionOptions = [
                'model' => $this->submissionClassName,
                "--datatables" => 'true',
                "--skip" => $toSkip,
                "--views" => "index,show",
                "--prefix" => strtolower(config('dwsync.generator.namespace')),
                '--fromTable'=>'true',
                '--tableName' => $tableSubmission
            ];
            $result['code'] = Artisan::call('infyom:api_scaffold', $submissionOptions);
            $result['output'] = Artisan::output();
        } else {//rollback
            $submissionOptions = [
                "model" => $this->submissionClassName,
                "type" => "api_scaffold",
                "--prefix" => strtolower(config('dwsync.generator.namespace'))
            ];
            $result['code'] = Artisan::call('infyom:rollback', $submissionOptions);
            $result['output'] = Artisan::output();
        }
        return $result;
    }

    private function setInfyomConfig($operation = "create")
    {
        //overwriting infyom config
        if ($operation == "create") {
            //add if needed
        } else {
            config(['infyom.laravel_generator.add_on.datatables' => true]);
            config(['infyom.laravel_generator.add_on.menu.enabled' => true]);
            config(['infyom.laravel_generator.tests' => true]);
            config(['infyom.laravel_generator.swagger' => true]);
        }
    }

    private function cleanDirtyGeneratedFile()
    {
        //Delete table before removing migration
        //There's no way to rollback single middle migration, so we need raw SQL
        $tableSubmission = Str::snake(str_plural($this->submissionClassName));
        Schema::dropIfExists($tableSubmission);

        //Remove json files
        $jsonFileSubmission = $this->submissionClassName . ".json";
        $jsonPath = config('infyom.laravel_generator.path.schema_files');
        deleteFilesInPath($jsonPath, $jsonFileSubmission);

        //Remove datatables files
        $dtFileSubmission = $this->submissionClassName . "DataTable.php";
        $dtPath = config('infyom.laravel_generator.path.datatables') . config('dwsync.generator.namespace') . "/";
        deleteFilesInPath($dtPath, $dtFileSubmission);

        //Make sure migration files are deleted (if note, new creation will freeze the app)
        $migrationFileSubmission = $tableSubmission . "_table.php";
        $migrationPath = config('infyom.laravel_generator.path.migration');
        deleteFilesInPath($migrationPath, $migrationFileSubmission);

        //Remove views folders
        $viewFolderSubmission = $tableSubmission;
        $viewPath = config('infyom.laravel_generator.path.views');
        $generatedViewPath = $viewPath . config('dwsync.generator.namespace') . "/";
        $result1 = File::cleanDirectory($generatedViewPath . $viewFolderSubmission);
        $result2 = File::deleteDirectory($generatedViewPath . $viewFolderSubmission);

        //Remove \n\n\n blank line break in menu
        $menuFile = $viewPath . "/" . config('infyom.laravel_generator.add_on.menu.menu_file');
        $menuContent = file_get_contents($menuFile);
        file_put_contents($menuFile, str_replace(["\n\n\n"], '', $menuContent));

        //Remove \n\n\n\n 02 blank lines in routes
        $routeFile = config('infyom.laravel_generator.path.routes');
        $apiRouteFile = config('infyom.laravel_generator.path.api_routes');
        $routeContent = file_get_contents($routeFile);
        $routeApiContent = file_get_contents($apiRouteFile);
        file_put_contents($routeFile, str_replace(["\n\n\n\n"], '', $routeContent));
        file_put_contents($apiRouteFile, str_replace(["\n\n\n\n"], '', $routeApiContent));
        app()['composer']->dumpOptimized();//refresh deleted files
    }

    public function getSubmissionFromDate($dateLastExported){
        $resultSubmission = $this->submissionClass::where('dwSubmittedAt','>',$dateLastExported)->get()->toArray(); //todo : check this date limit behind second
//        var_dump($resultSubmission);exit;
//        foreach ($submissions as $submission){
//            $resultSubmissionTmp = array();
//            $resultSubmissionTmp['id'] = $submission->id;
//            $resultSubmissionTmp['datasender_id'] = 'rep229';
//            $resultSubmissionTmp['date_soumission'] = $submission->dwSubmittedAt;
//            $resultSubmissionValueTmp = array();
//            $submissionValues = $this->submissionValueClass::where('submissionId',$submission->submissionId)->get();
//            foreach ($submissionValues as $submissionValue){
//                $resultSubmissionValueTmp[$submissionValue->xformQuestionId] = $submissionValue->value;
//            }
//            $resultSubmissionTmp['values'] = $resultSubmissionValueTmp;
//            $resultSubmission[] = $resultSubmissionTmp;
//        }
        return $resultSubmission;
    }

    public function updateSendStatus($submissionId, $statusCode){
        $submission = $this->submissionClass::find($submissionId);
        $submission->pushIdnrStatus = $statusCode;
        $submission->save();
    }

    /**
     * Get unique questions to manage duplicates
     *
     * Return list of questionId
     **/
    public function getUniqueQuestions(){
        //return query so it will be more flexible:
        //eg: $dwProject->getUniqueQuestions()->get()->toArray();
        $uniqueQuestions = $this->dwQuestions()->select('questionId')->where('isUnique', ">", 0);
        return $uniqueQuestions;
    }

    /**
     * Update duplicates of given submission to '0'
     *
     * Return list of condition ['column', 'operator', 'value'] (eg: ['name', '!=', 'smith']
     **/
    public function updateDuplicatesOf($currentSubmission, $uniqueQuestionsForDuplicates, $oldFlag = 1, $newFlag = 0){
        if(!$currentSubmission->id and count($uniqueQuestionsForDuplicates) > 0){//$currentSubmission doesn't exist yet AND there are unique questions for duplicates
            //Update
            $conditions = array();
            foreach ($uniqueQuestionsForDuplicates as $item){
                $questionId = $item['questionId'];
                $conditions[] = array($questionId, '=', $currentSubmission->$questionId);
            }
            //Check duplicates (based on is unique) #### Fire event
            //TODO : call external event definition

            $submissionQuery = $this->submissionClass::where($conditions)
                ->where('isValid', '=', $oldFlag);
            $submissions = $submissionQuery->get();
            foreach ($submissions as $submission){
                $submission->isValid = $newFlag;
                $submission->save();
            }
        }else{
            //Nothing
        }
    }
}
