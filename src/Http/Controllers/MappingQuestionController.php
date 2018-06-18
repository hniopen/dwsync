<?php

namespace Hni\Dwsync\Http\Controllers;

use Hni\Dwsync\DataTables\MappingQuestionDataTable;
use Hni\Dwsync\Http\Requests;
use Hni\Dwsync\Http\Requests\CreateMappingQuestionRequest;
use Hni\Dwsync\Http\Requests\UpdateMappingQuestionRequest;
use Hni\Dwsync\Models\DwQuestion;
use Hni\Dwsync\Models\MappingQuestion;
use Hni\Dwsync\Repositories\MappingQuestionRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Hni\Dwsync\Models\MappingProject;
use Illuminate\Support\Facades\DB;


class MappingQuestionController extends AppBaseController
{
    /** @var  MappingQuestionRepository */
    private $mappingQuestionRepository;

    public function __construct(MappingQuestionRepository $mappingQuestionRepo)
    {
        $this->mappingQuestionRepository = $mappingQuestionRepo;
    }

    /**
     * Display a listing of the MappingQuestion.
     *
     * @param MappingQuestionDataTable $mappingQuestionDataTable
     * @return Response
     */
    public function index(MappingQuestionDataTable $mappingQuestionDataTable)
    {
        $mappingQuestions = MappingQuestion::getAll();
        return view('dwsync::mapping_questions.index')
            ->with('mappingQuestions', $mappingQuestions);
    }

    /**
     * Show the form for creating a new MappingQuestion.
     *
     * @return Response
     */
    public function create()
    {
        $mappingProjects = MappingProject::getAllForSelect();
        $questions = DwQuestion::getAllForSelect();
        return view('dwsync::mapping_questions.create')
            ->with('questions', $questions)
            ->with('mappingProjects', $mappingProjects);
    }

    /**
     * Store a newly created MappingQuestion in storage.
     *
     * @param CreateMappingQuestionRequest $request
     *
     * @return Response
     */
    public function store(CreateMappingQuestionRequest $request)
    {
        $input = $request->all();

        $mappingQuestion = $this->mappingQuestionRepository->create($input);

        Flash::success('Mapping Question saved successfully.');

        return redirect(route('dwsync.mappingQuestions.index'));
    }

    /**
     * Display the specified MappingQuestion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mappingQuestion = $this->mappingQuestionRepository->findWithoutFail($id);

        if (empty($mappingQuestion)) {
            Flash::error('Mapping Question not found');

            return redirect(route('dwsync.mappingQuestions.index'));
        }

        return view('dwsync::mapping_questions.show')->with('mappingQuestion', $mappingQuestion);
    }

    /**
     * Show the form for editing the specified MappingQuestion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $mappingProjects = MappingProject::getAllForSelect();
        $questions = DwQuestion::getAllForSelect();
        $mappingQuestion = $this->mappingQuestionRepository->findWithoutFail($id);

        if (empty($mappingQuestion)) {
            Flash::error('Mapping Question not found');

            return redirect(route('dwsync.mappingQuestions.index'));
        }

        return view('dwsync::mapping_questions.edit')
            ->with('mappingQuestion', $mappingQuestion)
            ->with('questions', $questions)
            ->with('mappingProjects', $mappingProjects);
    }

    /**
     * Update the specified MappingQuestion in storage.
     *
     * @param  int              $id
     * @param UpdateMappingQuestionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMappingQuestionRequest $request)
    {
        $mappingQuestion = $this->mappingQuestionRepository->findWithoutFail($id);

        if (empty($mappingQuestion)) {
            Flash::error('Mapping Question not found');

            return redirect(route('dwsync.mappingQuestions.index'));
        }

        $mappingQuestion = $this->mappingQuestionRepository->update($request->all(), $id);

        Flash::success('Mapping Question updated successfully.');

        return redirect(route('dwsync.mappingQuestions.index'));
    }

    /**
     * Remove the specified MappingQuestion from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mappingQuestion = $this->mappingQuestionRepository->findWithoutFail($id);

        if (empty($mappingQuestion)) {
            Flash::error('Mapping Question not found');

            return redirect(route('dwsync.mappingQuestions.index'));
        }

        $this->mappingQuestionRepository->delete($id);

        Flash::success('Mapping Question deleted successfully.');

        return redirect(route('dwsync.mappingQuestions.index'));
    }
}
