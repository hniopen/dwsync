<?php

namespace Hni\Dwsync\Http\Controllers\API;

use Hni\Dwsync\Http\Requests\API\CreateMappingQuestionAPIRequest;
use Hni\Dwsync\Http\Requests\API\UpdateMappingQuestionAPIRequest;
use Hni\Dwsync\Models;
use Hni\Dwsync\Repositories\MappingQuestionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class MappingQuestionController
 * @package App\Http\Controllers\API\Mappingquestion
 */

class MappingQuestionAPIController extends AppBaseController
{
    /** @var  MappingQuestionRepository */
    private $mappingQuestionRepository;

    public function __construct(MappingQuestionRepository $mappingQuestionRepo)
    {
        $this->mappingQuestionRepository = $mappingQuestionRepo;
    }

    /**
     * Display a listing of the MappingQuestion.
     * GET|HEAD /mappingQuestions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->mappingQuestionRepository->pushCriteria(new RequestCriteria($request));
        $this->mappingQuestionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $mappingQuestions = $this->mappingQuestionRepository->all();

        return $this->sendResponse($mappingQuestions->toArray(), 'Mapping Questions retrieved successfully');
    }

    /**
     * Store a newly created MappingQuestion in storage.
     * POST /mappingQuestions
     *
     * @param CreateMappingQuestionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMappingQuestionAPIRequest $request)
    {
        $input = $request->all();

        $mappingQuestions = $this->mappingQuestionRepository->create($input);

        return $this->sendResponse($mappingQuestions->toArray(), 'Mapping Question saved successfully');
    }

    /**
     * Display the specified MappingQuestion.
     * GET|HEAD /mappingQuestions/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MappingQuestion $mappingQuestion */
        $mappingQuestion = $this->mappingQuestionRepository->findWithoutFail($id);

        if (empty($mappingQuestion)) {
            return $this->sendError('Mapping Question not found');
        }

        return $this->sendResponse($mappingQuestion->toArray(), 'Mapping Question retrieved successfully');
    }

    /**
     * Update the specified MappingQuestion in storage.
     * PUT/PATCH /mappingQuestions/{id}
     *
     * @param  int $id
     * @param UpdateMappingQuestionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMappingQuestionAPIRequest $request)
    {
        $input = $request->all();

        /** @var MappingQuestion $mappingQuestion */
        $mappingQuestion = $this->mappingQuestionRepository->findWithoutFail($id);

        if (empty($mappingQuestion)) {
            return $this->sendError('Mapping Question not found');
        }

        $mappingQuestion = $this->mappingQuestionRepository->update($input, $id);

        return $this->sendResponse($mappingQuestion->toArray(), 'MappingQuestion updated successfully');
    }

    /**
     * Remove the specified MappingQuestion from storage.
     * DELETE /mappingQuestions/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MappingQuestion $mappingQuestion */
        $mappingQuestion = $this->mappingQuestionRepository->findWithoutFail($id);

        if (empty($mappingQuestion)) {
            return $this->sendError('Mapping Question not found');
        }

        $mappingQuestion->delete();

        return $this->sendResponse($id, 'Mapping Question deleted successfully');
    }
}
