<?php

namespace Hni\Dwsync\Http\Controllers;

use Hni\Dwsync\DataTables\MappingProjectDataTable;
use Hni\Dwsync\Http\Requests;
use Hni\Dwsync\Http\Requests\CreateMappingProjectRequest;
use Hni\Dwsync\Http\Requests\UpdateMappingProjectRequest;
use Hni\Dwsync\Models\DwProject;
use Hni\Dwsync\Models\MappingProject;
use Hni\Dwsync\Repositories\MappingProjectRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Support\Facades\DB;

class MappingProjectController extends AppBaseController
{
    /** @var  MappingProjectRepository */
    private $mappingProjectRepository;

    public function __construct(MappingProjectRepository $mappingProjectRepo)
    {
        $this->mappingProjectRepository = $mappingProjectRepo;
    }

    /**
     * Display a listing of the MappingProject.
     *
     * @return Response
     */
    public function index()
    {
        $mappingProjects = MappingProject::getAll();
        return view('dwsync::mapping_projects.index')
            ->with('mappingProjects', $mappingProjects);
    }

    /**
     * Show the form for creating a new MappingProject.
     *
     * @return Response
     */
    public function create()
    {
        $projects = DwProject::where('entityType','!=','DS')
            ->orderBy('comment', 'asc')
            ->pluck('comment', 'id');
        return view('dwsync::mapping_projects.create',["projects"=>$projects]);
    }

    /**
     * Store a newly created MappingProject in storage.
     *
     * @param CreateMappingProjectRequest $request
     *
     * @return Response
     */
    public function store(CreateMappingProjectRequest $request)
    {
        $input = $request->all();

        $mappingProject = $this->mappingProjectRepository->create($input);

        Flash::success('Mapping Project saved successfully.');

        return redirect(route('dwsync.mappingProjects.index'));
    }

    /**
     * Display the specified MappingProject.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mappingProject = $this->mappingProjectRepository->findWithoutFail($id);

        if (empty($mappingProject)) {
            Flash::error('Mapping Project not found');

            return redirect(route('dwsync.mappingProjects.index'));
        }

        return view('dwsync::mapping_projects.show')->with('mappingProject', $mappingProject);
    }

    /**
     * Show the form for editing the specified MappingProject.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $projects = DwProject::where('entityType','!=','DS')
            ->orderBy('comment', 'asc')
            ->pluck('comment', 'id');
        $mappingProject = $this->mappingProjectRepository->findWithoutFail($id);
        if (empty($mappingProject)) {
            Flash::error('Mapping Project not found');

            return redirect(route('dwsync.mappingProjects.index'));
        }

        return view('dwsync::mapping_projects.edit')
            ->with('mappingProject', $mappingProject)
            ->with('projects', $projects);
    }

    /**
     * Update the specified MappingProject in storage.
     *
     * @param  int              $id
     * @param UpdateMappingProjectRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMappingProjectRequest $request)
    {
        $mappingProject = $this->mappingProjectRepository->findWithoutFail($id);

        if (empty($mappingProject)) {
            Flash::error('Mapping Project not found');

            return redirect(route('dwsync.mappingProjects.index'));
        }

        $mappingProject = $this->mappingProjectRepository->update($request->all(), $id);

        Flash::success('Mapping Project updated successfully.');

        return redirect(route('dwsync.mappingProjects.index'));
    }

    /**
     * Remove the specified MappingProject from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mappingProject = $this->mappingProjectRepository->findWithoutFail($id);

        if (empty($mappingProject)) {
            Flash::error('Mapping Project not found');

            return redirect(route('dwsync.mappingProjects.index'));
        }

        $this->mappingProjectRepository->delete($id);

        Flash::success('Mapping Project deleted successfully.');

        return redirect(route('dwsync.mappingProjects.index'));
    }
}
