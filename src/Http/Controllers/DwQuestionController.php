<?php

namespace Hni\Dwsync\Http\Controllers;

use Hni\Dwsync\Http\Requests\CreateDwQuestionRequest;
use Hni\Dwsync\Http\Requests\UpdateDwQuestionRequest;
use Hni\Dwsync\Models\DwQuestion;
use Hni\Dwsync\Repositories\DwQuestionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Hni\Dwsync\Models\DwProject;

class DwQuestionController extends AppBaseController
{
    /** @var  DwQuestionRepository */
    private $dwQuestionRepository;

    public function __construct(DwQuestionRepository $dwQuestionRepo)
    {
        $this->dwQuestionRepository = $dwQuestionRepo;
    }

    /**
     * Display a listing of the DwQuestion.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->dwQuestionRepository->pushCriteria(new RequestCriteria($request));
        $dwQuestions = $this->dwQuestionRepository->all();

        return view('dwsync::dw_questions.index')
            ->with('dwQuestions', $dwQuestions);
    }

    /**
     * Display a listing of the DwQuestion for a specifique project
     *
     * @param Request $request
     * @return Response
     */
    public function listQuestions($projectId)
    {
        $dwProject = DwProject::findOrFail($projectId);
        $dwQuestions = $dwProject->dwQuestions()->get();
        return view('dwsync::dw_questions.index')
            ->with('dwQuestions', $dwQuestions);
    }

    /**
     * Show the form for creating a new DwQuestion.
     *
     * @return Response
     */
    public function create()
    {
        $dwProjectList = DwProject::pluck('comment','id');
        $defaultIdnr = collect([null => 'No idnr']);
        $dwIdnrList = DwProject::where('entityType', 'I')->pluck('comment','id');
        $dwIdnrList = $defaultIdnr->union($dwIdnrList);
        return view('dwsync::dw_questions.create', compact('dwProjectList', 'dwIdnrList'));
    }

    /**
     * Store a newly created DwQuestion in storage.
     *
     * @param CreateDwQuestionRequest $request
     *
     * @return Response
     */
    public function store(CreateDwQuestionRequest $request)
    {
        $input = $request->all();
        $dwQuestion = $this->dwQuestionRepository->create($input);
        $message = 'Dw Question saved successfully.';

        if(isset($input['forAjax'])){
            return ['message'=>$message, 'entity'=>$dwQuestion];
        }else{
            Flash::success($message);
            return redirect(route('dwsync.dwQuestions.index'));
        }
    }

    /**
     * Store a newly created DwQuestion in storage.
     *
     * @param CreateDwQuestionRequest $request
     *
     * @return Response
     */
    public function createFromSubmissions(CreateDwQuestionRequest $request)
    {
        $input = $request->all();
        $dwProjectList = DwProject::pluck('comment','id');
        return view('dwsync::dw_questions.create_from_submissions', compact('dwProjectList'));
    }

    /**
     * Check questions from Dw
     *
     * @param CreateDwQuestionRequest $request
     *
     * @return Response
     */
    public function checkFromSubmissions(CreateDwQuestionRequest $request)
    {
        $input = $request->all();
        $checked = 1;
        $selectedProject = DwProject::find($input['projectId']);
        $tCheckResult = $selectedProject->checkQuestionsFromDw();
        $questionsList = $tCheckResult['questions'];
        $error = $tCheckResult['error'];

        $dwProjectList = DwProject::pluck('comment','id');
        $defaultIdnr = collect([null => 'No idnr']);
        $dwIdnrList = DwProject::where('entityType', 'I')->pluck('comment','id');
        $dwIdnrList = $defaultIdnr->union($dwIdnrList);
        return view('dwsync::dw_questions.create_from_submissions',
            compact('dwProjectList', 'checked', 'selectedProject', 'questionsList', 'error', 'dwIdnrList'));
    }

    /**
     * Store a newly created DwQuestion in storage.
     *
     * @param CreateDwQuestionRequest $request
     *
     * @return Response
     */
    public function storeFromSubmissions(CreateDwQuestionRequest $request)
    {
        $input = $request->all();
        $questionNumber = 0 ;
        //TODO: implement creation:
        //REVIEW : This seems  unused feature
        //$dwQuestion = $this->dwQuestionRepository->create($input);

        Flash::success("$questionNumber question(s) saved successfully.");

        return redirect(route('dwsync.dwQuestions.index'));
    }

    /**
     * Store a newly created DwQuestion in storage.
     *
     * @param CreateDwQuestionRequest $request
     *
     * @return Response
     */
    public function createFromXlsform(CreateDwQuestionRequest $request)
    {
        $input = $request->all();
        $dwProjectList = DwProject::pluck('comment','id');
        return view('dwsync::dw_questions.create_from_xlsform', compact('dwProjectList'));
    }

    /**
     * Store a newly created DwQuestion in storage.
     *
     * @param CreateDwQuestionRequest $request
     *
     * @return Response
     */
    public function storeFromXlsform(CreateDwQuestionRequest $request)
    {
        $input = $request->all();
        $questionNumber = 0 ;
        //TODO: implement creation:
        //$dwQuestion = $this->dwQuestionRepository->create($input);

        Flash::success("$questionNumber question(s) saved successfully.");

        return redirect(route('dwsync.dwQuestions.index'));
    }

    /**
     * Display the specified DwQuestion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $dwQuestion = $this->dwQuestionRepository->findWithoutFail($id);

        if (empty($dwQuestion)) {
            Flash::error('Dw Question not found');

            return redirect(route('dwsync.dwQuestions.index'));
        }

        return view('dwsync::dw_questions.show')->with('dwQuestion', $dwQuestion);
    }

    /**
     * Show the form for editing the specified DwQuestion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $dwQuestion = $this->dwQuestionRepository->findWithoutFail($id);

        if (empty($dwQuestion)) {
            Flash::error('Dw Question not found');

            return redirect(route('dwsync.dwQuestions.index'));
        }
        $dwProjectList = DwProject::pluck('comment','id');
        $defaultIdnr = collect([null => 'No idnr']);
        $dwIdnrList = DwProject::where('entityType', 'I')->pluck('comment','id');
        $dwIdnrList = $defaultIdnr->union($dwIdnrList);
        return view('dwsync::dw_questions.edit', compact('dwQuestion','dwProjectList', 'dwIdnrList'));
    }

    /**
     * Update the specified DwQuestion in storage.
     *
     * @param  int              $id
     * @param UpdateDwQuestionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDwQuestionRequest $request)
    {
        $dwQuestion = $this->dwQuestionRepository->findWithoutFail($id);

        if (empty($dwQuestion)) {
            Flash::error('Dw Question not found');

            return redirect(route('dwsync.dwQuestions.index'));
        }
        $input = $request->all();
        $dwQuestion = $this->dwQuestionRepository->update($input, $id);

        $message = 'Dw Question updated successfully.';
        if(isset($input['forAjax'])){
            return ['message'=>$message, 'entity'=>$dwQuestion];
        }else{
            Flash::success($message);
            return redirect(route('dwsync.dwQuestions.index'));
        }
    }

    /**
     * Remove the specified DwQuestion from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $dwQuestion = $this->dwQuestionRepository->findWithoutFail($id);

        if (empty($dwQuestion)) {
            Flash::error('Dw Question not found');

            return redirect(route('dwsync.dwQuestions.index'));
        }

        $this->dwQuestionRepository->delete($id);

        Flash::success('Dw Question deleted successfully.');

        return redirect(route('dwsync.dwQuestions.index'));
    }
}
