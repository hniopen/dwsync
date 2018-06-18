<?php

namespace Hni\Dwsync\Http\Controllers\API;

use App\Http\Requests\API\Mappingproject\CreateMappingProjectAPIRequest;
use App\Http\Requests\API\Mappingproject\UpdateMappingProjectAPIRequest;
use Hni\Dwsync\Models\MappingProject;
use Hni\Dwsync\Repositories\MappingProjectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class MappingProjectController
 * @package App\Http\Controllers\API\Mappingproject
 */

class MappingProjectAPIController extends AppBaseController
{
    /** @var  MappingProjectRepository */
    private $mappingProjectRepository;

    public function __construct(MappingProjectRepository $mappingProjectRepo)
    {
        $this->mappingProjectRepository = $mappingProjectRepo;
    }

    /**
     * Display a listing of the MappingProject.
     * GET|HEAD /mappingProjects
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->mappingProjectRepository->pushCriteria(new RequestCriteria($request));
        $this->mappingProjectRepository->pushCriteria(new LimitOffsetCriteria($request));
        $mappingProjects = $this->mappingProjectRepository->all();

        return $this->sendResponse($mappingProjects->toArray(), 'Mapping Projects retrieved successfully');
    }

    /**
     * Store a newly created MappingProject in storage.
     * POST /mappingProjects
     *
     * @param CreateMappingProjectAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMappingProjectAPIRequest $request)
    {
        $input = $request->all();

        $mappingProjects = $this->mappingProjectRepository->create($input);

        return $this->sendResponse($mappingProjects->toArray(), 'Mapping Project saved successfully');
    }

    /**
     * Display the specified MappingProject.
     * GET|HEAD /mappingProjects/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MappingProject $mappingProject */
        $mappingProject = $this->mappingProjectRepository->findWithoutFail($id);

        if (empty($mappingProject)) {
            return $this->sendError('Mapping Project not found');
        }

        return $this->sendResponse($mappingProject->toArray(), 'Mapping Project retrieved successfully');
    }

    /**
     * Update the specified MappingProject in storage.
     * PUT/PATCH /mappingProjects/{id}
     *
     * @param  int $id
     * @param UpdateMappingProjectAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMappingProjectAPIRequest $request)
    {
        $input = $request->all();

        /** @var MappingProject $mappingProject */
        $mappingProject = $this->mappingProjectRepository->findWithoutFail($id);

        if (empty($mappingProject)) {
            return $this->sendError('Mapping Project not found');
        }

        $mappingProject = $this->mappingProjectRepository->update($input, $id);

        return $this->sendResponse($mappingProject->toArray(), 'MappingProject updated successfully');
    }

    /**
     * Remove the specified MappingProject from storage.
     * DELETE /mappingProjects/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MappingProject $mappingProject */
        $mappingProject = $this->mappingProjectRepository->findWithoutFail($id);

        if (empty($mappingProject)) {
            return $this->sendError('Mapping Project not found');
        }

        $mappingProject->delete();

        return $this->sendResponse($id, 'Mapping Project deleted successfully');
    }
}
