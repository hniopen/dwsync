<?php

namespace Hni\Dwsync\Http\Controllers;

use Hni\Dwsync\Http\Requests\CreateDwProjectRequest;
use Hni\Dwsync\Http\Requests\UpdateDwProjectRequest;
use Hni\Dwsync\Models\DwProject;
use Hni\Dwsync\Models\DwQuestion;
use Hni\Dwsync\Models\HascMada;
use Hni\Dwsync\Models\MappingProject;
use Hni\Dwsync\Models\MappingQuestion;
use Hni\Dwsync\Repositories\DwProjectRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Hni\Dwsync\Models\DwEntityType;
use Hni\Dwsync\Models\OpenRosa;
use DateTime;
use SimpleXMLElement;
use Illuminate\Support\Facades\DB;

class DwProjectController extends AppBaseController
{
    /** @var  DwProjectRepository */
    private $dwProjectRepository;

    public function __construct(DwProjectRepository $dwProjectRepo)
    {
        $this->dwProjectRepository = $dwProjectRepo;
    }

    /**
     * Display a listing of the DwProject.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->dwProjectRepository->pushCriteria(new RequestCriteria($request));
        $dwProjects = $this->dwProjectRepository->scopeQuery(function($query){
            return $query->orderBy('entityType','asc');
        })->all();
        foreach ($dwProjects as $project){
            $project->refreshLastSubmission();
        }
        return view('dwsync::dw_projects.index', compact('dwProjects'));
    }

    /**
     * Show list of actions for DwProjects.
     *
     * @return Response
     */
    public function extra($id)
    {
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);

        if (empty($dwProject)) {
            Flash::error('Dw Project not found');

            return redirect(route('dwsync.dwProjects.index'));
        }
        $dwProject->refreshLastSubmission();
        $dwLastSubmission = $dwProject->getLastSubmission();
        if(!empty($dwLastSubmission)){
            $lastDateSubmission= $dwLastSubmission->dwSubmittedAt;
            $lastDateSubmission= date('d/m/Y H:i:s',strtotime($lastDateSubmission));
        }
        else{
            $lastDateSubmission = '01/01/2015 01:01:01';
        }
        $dwProject->initAbstractAttributes();
        return view('dwsync::dw_projects.extra', compact('dwProject','lastDateSubmission'));
    }

    /**
     * Show the form for creating a new DwProject.
     *
     * @return Response
     */
    public function create()
    {
        $dwEntityTypeList = DwEntityType::pluck('comment','type');
        return view('dwsync::dw_projects.create', compact('dwEntityTypeList'));
    }

    /**
     * Store a newly created DwProject in storage.
     *
     * @param CreateDwProjectRequest $request
     *
     * @return Response
     */
    public function store(CreateDwProjectRequest $request)
    {
        ini_set('max_execution_time', 240);//4min
        $input = $request->all();
        $input['credential'] = fctReversibleCrypt($input['credential']);
        $dwProject = $this->dwProjectRepository->create($input);
        $dwProject->generateSubmissionModels();
        $dwProject->generateSubmissionExtendedModels();

        Flash::success('Dw Project saved successfully.');
        return redirect(route('dwsync.dwProjects.index'));
    }

    /**
     * Display the specified DwProject.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);

        if (empty($dwProject)) {
            Flash::error('Dw Project not found');

            return redirect(route('dwsync.dwProjects.index'));
        }
        return view('dwsync::dw_projects.show', compact('dwProject'));
    }

    /**
     * Show the form for editing the specified DwProject.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);

        if (empty($dwProject)) {
            Flash::error('Dw Project not found');

            return redirect(route('dwsync.dwProjects.index'));
        }
        $dwEntityTypeList = DwEntityType::pluck('comment','type');
        return view('dwsync::dw_projects.edit', compact('dwProject', 'dwEntityTypeList'));
    }

    /**
     * Update the specified DwProject in storage.
     *
     * @param  int              $id
     * @param UpdateDwProjectRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDwProjectRequest $request)
    {
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);

        if (empty($dwProject)) {
            Flash::error('Dw Project not found');

            return redirect(route('dwsync.dwProjects.index'));
        }
        $input = $request->all();
        $input['credential'] = fctReversibleCrypt($input['credential']);
        $dwProject = $this->dwProjectRepository->update($input, $id);

        Flash::success('Dw Project updated successfully.');

        return redirect(route('dwsync.dwProjects.index'));
    }

    /**
     * Remove the specified DwProject from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);

        if (empty($dwProject)) {
            Flash::error('Dw Project not found');

            return redirect(route('dwsync.dwProjects.index'));
        }
        $dwProject->initAbstractAttributes();
        $dwProject->generateSubmissionModels("rollback");
        $this->dwProjectRepository->delete($id);

        Flash::success('Dw Project deleted successfully.');

        return redirect(route('dwsync.dwProjects.index'));
    }
    /**
     * Check questions from Dw submissions
     *
     * @return Response
     */
    public function checkFromSubmissions($id,Request $request)
    {
        $input = $request->all();
        $fromDate = $input['fromDate'];
        $toDate = $input['toDate'];
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);
        $tCheckResult = [];
        if (empty($dwProject)) {
            $tCheckResult['message'] = ['statusCode'=>1, 'text'=>'DW project not found'];
        }else{
            $dwProject->setStartDate($fromDate);
            $dwProject->setEndDate($toDate);
            $tCheckResult = $dwProject->checkQuestionsFromDwSubmissions();
        }
        return response()->json($tCheckResult);
    }

    /**
     * Insert questions from Dw submissions
     *
     * @return Response
     */
    public function insertFromSubmissions(Request $request)
    {
        $inputs = $request->all();
        $projectId = $inputs['projectId'];
        $dwProject = $this->dwProjectRepository->findWithoutFail($inputs['projectId']);
        $tResult = [];
        $insertCount = 0;
        $updateCount = 0;
        $toInsertQuestion = [];
        if (empty($dwProject)) {
            $tResult['message'] = ['statusCode'=>1, 'text'=>'DW project not found'];
        }else{
            $questions = $inputs['questions'];
            foreach ($questions as $item){
                $uniqueColumns = ['projectId'=>$projectId,'questionId'=>$item];
                $currentDwQuestion = DwQuestion::firstOrNew($uniqueColumns);
                $currentDwQuestion->xformQuestionId = $item;
                $currentDwQuestion->labelDefault = $item;
                if($currentDwQuestion->id)
                    $updateCount++;
                else{
                    $insertCount++;
                    $toInsertQuestion[$item] = ['type'=>'TEXT'];//no known db type
                }
                $currentDwQuestion->save();
            }
            if($insertCount > 0){
                $dwProject->addQuestionsToModel($toInsertQuestion);
            }
            $tResult['message'] = ['statusCode'=>'', 'text'=>"Saved question(s) : $insertCount insert(s), $updateCount update(s)"];
        }
        return response()->json($tResult);
    }

    /**
     * Check questions from Dw xform
     *
     * @return Response
     */
    public function checkFromXform($id)
    {
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);
        $tCheckResult = [];
        if (empty($dwProject)) {
            $tCheckResult['message'] = ['statusCode'=>1, 'text'=>'DW project not found'];
        }else{
            $tCheckResult = $dwProject->checkQuestionsFromDwXform();
        }
        return response()->json($tCheckResult);
    }

    /**
     * Insert questions from Dw xform
     *
     * @return Response
     */
    public function insertFromXform(Request $request)
    {
        $inputs = $request->all();
        $projectId = $inputs['projectId'];
        $dwProject = $this->dwProjectRepository->findWithoutFail($inputs['projectId']);
        $tResult = [];
        $insertCount = 0;
        $updateCount = 0;
        $toInsertQuestion = [];
        if (empty($dwProject)) {
            $tResult['message'] = ['statusCode'=>1, 'text'=>'DW project not found'];
        }else{
            $questions = $inputs['questions'];
            foreach ($questions as $item => $tValue){
                $uniqueColumns = ['projectId'=>$projectId,'questionId'=>$item];
                $currentDwQuestion = DwQuestion::firstOrNew($uniqueColumns);
                $currentDwQuestion->xformQuestionId = $item;
                $currentDwQuestion->labelDefault = $tValue['label'];
                $currentDwQuestion->dataType = $tValue['type'];
                if($currentDwQuestion->id)
                    $updateCount++;
                else{
                    $insertCount++;
                    $toInsertQuestion[$item] = ['type'=>fctGetDBTypeFromXform($tValue['type'])];
                }
                $currentDwQuestion->save();
            }
            if($insertCount > 0){
                $dwProject->addQuestionsToModel($toInsertQuestion);
            }
            $tResult['message'] = ['statusCode'=>'', 'text'=>"Saved question(s) : $insertCount insert(s), $updateCount update(s)"];
        }
        return response()->json($tResult);
    }

    /**
     * Check questions from Dw xls (xlsform)
     *
     * @return Response
     */
    public function checkFromXls($id, Request $request)
    {
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);
        $tCheckResult = [];
        if (empty($dwProject)) {
            $tCheckResult['message'] = ['statusCode'=>1, 'text'=>'DW project not found'];
        }else{
            $file = $request->file('xlsform');
            $tCheckResult = $dwProject->checkQuestionsFromDwXls($file);

            $destinationPath = config('dwsync.xlsform.uploadPath');
            $file->move($destinationPath,$file->getClientOriginalName());
        }
        return response()->json($tCheckResult);
    }
    /**
     * Insert questions from xls form
     *
     * @return Response
     */
    public function insertFromXls(Request $request)
    {
        $inputs = $request->all();
        $projectId = $inputs['projectId'];
        $dwProject = $this->dwProjectRepository->findWithoutFail($inputs['projectId']);
        $tResult = [];
        $insertCount = 0;
        $updateCount = 0;
        $toInsertQuestion = [];
        if (empty($dwProject)) {
            $tResult['message'] = ['statusCode'=>1, 'text'=>'DW project not found'];
        }else{
            $questions = $inputs['questions'];
            foreach ($questions as $item => $tValue){
                $uniqueColumns = ['projectId'=>$projectId,'questionId'=>$item];
                $currentDwQuestion = DwQuestion::firstOrNew($uniqueColumns);
                $currentDwQuestion->xformQuestionId = $tValue['name'];
                $currentDwQuestion->labelDefault = $tValue['label'];
                $currentDwQuestion->dataType = $tValue['type'];
                $currentDwQuestion->order = $tValue['order'];
                $currentDwQuestion->path = $tValue['path'];
                if($currentDwQuestion->id)
                    $updateCount++;
                else{
                    $insertCount++;
                    $toInsertQuestion[$item] = ['type'=>fctGetDBTypeFromXls($tValue['type'])];
                }
                $currentDwQuestion->save();
            }
            if($insertCount > 0){
                $dwProject->addQuestionsToModel($toInsertQuestion);
            }
            $tResult['message'] = ['statusCode'=>'', 'text'=>"Saved question(s) : $insertCount insert(s), $updateCount update(s)"];
        }
        return response()->json($tResult);
    }

    /**
     * Check existing related questions
     *
     * @return Response
     */
    public function checkExistingQuestions($id)
    {
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);
        $tCheckResult = [];
        if (empty($dwProject)) {
            $tCheckResult['message'] = ['statusCode'=>1, 'text'=>'DW project not found'];
        }else{
            $tAllQuestions = $dwProject->dwQuestions;
            $output = [];
            foreach($tAllQuestions as $quest){
                $output[$quest->xformQuestionId]['type'] = $quest->dataType;
                $output[$quest->xformQuestionId]['label'] = $quest->labelDefault;
            }
            $tCheckResult['result'] = $tAllQuestions;
            $tCheckResult['questions'] = $output;
            $tCheckResult['message'] = ['statusCode'=>0, 'text'=>$dwProject->questCode." | ".$dwProject->comment];
        }

        return response()->json($tCheckResult);
    }

    /**
     * Insert questions from xls form
     *
     * @return Response
     */
    public function removeExistingQuestions(Request $request)
    {
        $inputs = $request->all();
        $projectId = $inputs['projectId'];
        $dwProject = $this->dwProjectRepository->findWithoutFail($inputs['projectId']);
        $tResult = [];
        $count = 0;
        if (empty($dwProject)) {
            $tResult['message'] = ['statusCode'=>1, 'text'=>'DW project not found'];
        }else{
            $questions = $inputs['questions'];
            $toDeleteQuestion = [];
            foreach ($questions as $item => $tValue){
                $uniqueColumns = ['projectId'=>$projectId,'xformQuestionId'=>$item];
                $currentDwQuestion = DwQuestion::where($uniqueColumns);
                $currentDwQuestion->forceDelete();
                $toDeleteQuestion[$item] = $item;//only key matters
                $count++;
            }
            if($count > 0)
                $dwProject->removeQuestionsInModel($toDeleteQuestion);
            $tResult['message'] = ['statusCode'=>'', 'text'=>"Removed question(s) : $count"];
        }
        return response()->json($tResult);
    }

    /**
     * Sync data from Dw
     *
     * @return Response
     */
    public function sync($id, Request $request)
    {
        set_time_limit(0);//forever
        ini_set('memory_limit','512M');
        DB::disableQueryLog();
        $input = $request->all();
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);
        $tCheckResult = [];
        if (empty($dwProject)) {
            $tCheckResult['message'] = ['statusCode'=>'', 'text'=>'DW project not found'];
        }else{
            $dwProject->initAbstractAttributes();
            if(isset($input['from'])){
                $fromDate = $input['from'];
                $dwProject->setStartDate($fromDate);
            }
            $tCheckResult = $dwProject->sync();
        }
        return response()->json($tCheckResult);
    }

    /**
     * Sync all marked autosync from Dw
     *
     * @return Response
     */
    public function syncAllMarked(Request $request)
    {
        set_time_limit(0);//forever
        ini_set('memory_limit','512M');
        DB::disableQueryLog();
        $input = $request->all();
        $dwProjects = DwProject::where('autoSync', '>', 0)->get();
        $tCheckResult = [];
        if (empty($dwProjects)) {
            $tCheckResult['message'] = ['statusCode'=>'', 'text'=>'No marked project found'];
        }else{
            foreach ($dwProjects as $dwProject){
                $dwProject->initAbstractAttributes();
                if(isset($input['from'])){
                    $fromDate = $input['from'];
                    $dwProject->setStartDate($fromDate);
                }
                $tCheckResult[] = $dwProject->sync();
            }
        }
        return response()->json($tCheckResult);
    }

    /**
     * Update project from DB
     *
     * @return Response
     */
    public function updateModelFromDB($id, Request $request)
    {
        $input = $request->all();
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);
        $tResut = [];
        if (empty($dwProject)) {
            $tResut['message'] = ['statusCode'=>'', 'text'=>'DW project not found'];
        }else{
            $dwProject->initAbstractAttributes();
            $dwProject->generateSubmissionModels("update");
            $tResut['message'] = ["project model with id ".$id." updated !"];
        }
        return response()->json($tResut);
    }

    /**
     * Sync all from Dw
     *
     * @return Response
     */
    public function syncAll()
    {
        set_time_limit(0);//forever
        ini_set('memory_limit','512M');
        DB::disableQueryLog();
        $dwProjects = $this->dwProjectRepository->scopeQuery(function($query){
                return $query->orderBy('entityType','asc');
            })->where;
        $tCheckResult = [];
        if(empty($dwProjects)){
            $tCheckResult['message'] = ['statusCode'=>'', 'text'=>'DW project not found'];
        }else{
            foreach($dwProjects as $project){
                $tCheckResult[$project->questCode] = $project->sync();
            }
        }
        return response()->json($tCheckResult);
    }

    /**
     * push all submission in projectMapping into Dw
     *
     * @return Response
     */
    public function pushIdnr(){
        set_time_limit(0);//forever
        ini_set('memory_limit','512M');
        DB::disableQueryLog();
        $submission_url = config('dwsync.dwBaseUrl').'/xforms/submission';
        $dwLogin = config('dwsync.dwLogin');
        $dwPassword = config('dwsync.dwPassword');

        if(empty($dwLogin) || empty($dwPassword)){
            $pushResults = array(
                'status'=> 'error',
                'message'=> 'Datawinners account not set'
                );
            return response()->json($pushResults);
        }
        $credentials = array("username"=>$dwLogin,
            "password"=>$dwPassword);
        // the value of the short code should not be empty or '-1'
        $shortCodeField = 'quest_9';
        //initialize Open rosa
        $openRosa = new Openrosa();
        // create the xml file
        $createdFiles = array();
        // push result
        $pushResults = array();
        $pushResults['nbSuccess'] = 0;
        $pushResults['nbFail'] = 0;
        // get questionnaire Mapping
        $mapProjects = MappingProject::where('isActive', 1)->get();

        foreach ($mapProjects as $mapProject){
            $project1 = DwProject::findOrFail($mapProject->project1);
            $project1->initAbstractAttributes();
            $project2 = DwProject::findOrFail($mapProject->project2);
            $project2->initAbstractAttributes();
            $questionnaireUID = $project2->longQuestCode;
            if(empty($questionnaireUID)){
                return response()->json(array("status"=>'error','message'=>'Questionnaire long code note set, please set it!'));
            }
            else{
                // get new inserted or submitted data
                $dateLastExported = !empty($mapProject->dateLastExported)?
                    $mapProject->dateLastExported : '2011-01-01 00:00:00';
                $lastDateObject = new DateTime($dateLastExported);
                $lastDateObjectTmp = new DateTime($dateLastExported);
                $submissions = $project1->getSubmissionFromDate($dateLastExported);
                if(!empty($submissions)){
                    $mappingQuestions = MappingQuestion::where('mappingProjectId',$mapProject->id)->get();
                    foreach ($submissions as $submission){
//                        if(empty($submission[$shortCodeField])
//                            || $submission[$shortCodeField] == '-1'
//                            || $submission[$shortCodeField] == -1
//                        ){
//                            continue;
//                        }
                        $xml = new SimpleXMLElement('<?xml version="1.0" ?><data></data>');
                        $xml->addAttribute('id', $questionnaireUID);
                        $xml->addChild('eid',$submission['datasenderId']);

                        foreach ($mappingQuestions as $mapQuestion){
                            $question1 = DwQuestion::find($mapQuestion->question1);
                            $question2 = DwQuestion::find($mapQuestion->question2);

                            $customise = $mapQuestion->functions;
                            if(!empty($question1) && !empty($submission[$question1->questionId])){
                                $originalValue = $submission[$question1->questionId];
                            }
                            if(!empty($customise)){

                                if($customise == 'generateCode')
                                {
                                    $arrayResult =
                                        HascMada::generateVillageCode($submission,$mapQuestion->arg1);
                                    $arrayArg2 = explode(',',$mapQuestion->arg2);

                                    if(!empty($arrayResult['value'])){
                                        foreach ($arrayArg2 as $arg2){
                                            $arrayResult['value'][$arg2] = $submission[$arg2];
                                        }
                                        $arrayResult['value']['date_submission'] = $submission['dwSubmittedAt'];
                                        if(empty($arrayResult['id'])){
                                            HascMada::create($arrayResult['value']);
                                        }
                                        else{
                                            HascMada::where('id', '=', $arrayResult['id'])
                                                ->update($arrayResult['value']);
                                        }
                                       $value = $arrayResult['value']['code_village'];
                                    }
                                    else{
                                        echo "one empty";
                                        exit();
                                    }
                                }
                                elseif($customise == 'upperCase')
                                {
                                    $value=strtoupper(str_replace("_"," ",$originalValue));
                                }
                                else{
                                    $value = $customise($originalValue
                                        , $mapQuestion->arg1
                                        , $mapQuestion->arg2
                                    );
                                }
                                $xml->addChild($question2->xformQuestionId,$value);
                            }
                            else{

                                $xml->addChild($question2->xformQuestionId,$originalValue);
                            }
                        }
                        $xml->addChild('form_code',$project2->questCode);
                        $date = new DateTime();
                        $fileName = 'submission_'.$date->format('Y-m-d_H_i_s_v').'_'.$submission['id'].'.xml';
                        $filePath = 'xml/submission_'.$date->format('Y-m-d_H_i_s_v').'_'.$submission['id'].'.xml';
                        $createdFiles[] = array('submissionId'=>$submission['id'],
                            'file_name'=>$fileName,
                            'date_soumission' => $submission['dwSubmittedAt']);
                        $xml->asXML($filePath);
                    }
                }
                // send the created xml to datawinners
                $pushResults['nbTotal'] = count($createdFiles);
                foreach ($createdFiles as $file){
                    $xmlFileDetail = array();
                    $xmlFileDetail['file_name'] = $file['file_name'];
                    $xmlFileDetail['file_path'] = realpath(public_path().'/xml/'.$file['file_name']);
                    $response = $openRosa
                        ->submit_data($submission_url, $xmlFileDetail, array(), $credentials);
//                    var_dump($response);
                    if($response['status_code'] == 201 && empty($response['xml'])){
                        $pushResults['nbSuccess'] =
                            empty($pushResults['nbSuccess'])? 1 : $pushResults['nbSuccess'] + 1;
                    }
                    else{
                        $pushResults['nbFail'] =
                            empty($pushResults['nbFail'])? 1 : $pushResults['nbFail'] + 1;
                    }

                    $project1->updateSendStatus($file['submissionId'], $response['status_code']);
                    /* Date Last Update Tracking ... ***/
                    $dateSoum = new DateTime($file['date_soumission']);
                    if($lastDateObjectTmp < $dateSoum){
                        $lastDateObjectTmp = $dateSoum;
                    }
                }

                /* Save all date Last exported Update ...***/
                if($lastDateObject != $lastDateObjectTmp){
                    $mappingProject = MappingProject::findOrFail($mapProject->id);
                    $mappingProject->dateLastExported = $lastDateObjectTmp->format('Y-m-d H:i:s');
                    $mappingProject->save();
                }
            }
        }
        $pushResults['status'] = 'success';
        return response()->json($pushResults);
    }

    /**
     * List submissions & values in a single line
     */
    public function listSubmissionsAndValuesInLine($id){
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);
        $dwProject->initAbstractAttributes();
        $valueMethod = $dwProject->getSubmissionValueRelationMethod();
        $submissionsQuery = $dwProject->dwSubmissions();
        //$submissionsSql = $submissionsQuery->toSql();//For debuging
        $submissionCollection = $submissionsQuery->get();
        //Note : you can use all collection methods for extra : filter, ...
        foreach ($submissionCollection as $key => $sub){
            $currentValuesListQuery = $sub->{$valueMethod}();
            //$currentValuesListSql = $currentValuesListQuery->toSql();//For debuging
            $currentValuesListCollection = $currentValuesListQuery->get()->keyBy('xformQuestionId');
//            $currentValuesListArray = $currentValuesListCollection->toArray();
            $sub->values = $currentValuesListCollection;
        }
        return $submissionCollection->toJson();
    }

    public function createSubmissionAndValuesView($id){
        $dwProject = $this->dwProjectRepository->findWithoutFail($id);
        $dwProject->initAbstractAttributes();
        $submissionClass = $dwProject->getSubmissionClass();
        $valueClass = $dwProject->getSubmissionValueClass();
        $submissionTable = (new $submissionClass())->getTable();
        $valuesTable = (new $valueClass())->getTable();
        $rowToColumnSql = $dwProject->generateSqlForSubmissionValueJoin();
        $sqlSubmissionValue = "select s.* , $rowToColumnSql from $valuesTable v inner join $submissionTable s GROUP BY v.submissionId ";
        $sqlCreateView = "create view ". $dwProject->getDbSubValueViewName() ." as ( $sqlSubmissionValue )";
        $output['sql']  = $sqlCreateView;
        $output['status'] = "Auto create will be defined later, copy paste the sql below";//TODO fix create statement DB::statement($sqlCreateView);
        return response()->json($output);
    }

    public function countIfType($entityType, Request $request){
        $query=DwProject::whereRaw("entityType = '".$entityType."'")->count();
        $resultOnArray=['value'=>$query];
        return response()->json($resultOnArray);
    }

    public function countDataIfType($entityType, Request $request){
        $dwProjets = DwProject::whereRaw("entityType = '".$entityType."'");
        $count = 0;
        foreach ($dwProjets->get() as $project){
            $project->initAbstractAttributes();
            $count += $project->dwSubmissions()->count();
        }
        $resultOnArray=['value'=>$count];
        return response()->json($resultOnArray);
    }
}
