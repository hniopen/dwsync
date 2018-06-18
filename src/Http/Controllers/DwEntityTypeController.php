<?php

namespace Hni\Dwsync\Http\Controllers;

use Hni\Dwsync\Repositories\CreateDwEntityTypeRequest;
use Hni\Dwsync\Http\Requests\UpdateDwEntityTypeRequest;
use Hni\Dwsync\Repositories\DwEntityTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class DwEntityTypeController extends AppBaseController
{
    /** @var  DwEntityTypeRepository */
    private $dwEntityTypeRepository;

    public function __construct(DwEntityTypeRepository $dwEntityTypeRepo)
    {
        $this->dwEntityTypeRepository = $dwEntityTypeRepo;
    }

    /**
     * Display a listing of the DwEntityType.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->dwEntityTypeRepository->pushCriteria(new RequestCriteria($request));
        $dwEntityTypes = $this->dwEntityTypeRepository->all();

        return view('dwsync::dw_entity_types.index')
            ->with('dwEntityTypes', $dwEntityTypes);
    }

    /**
     * Show the form for creating a new DwEntityType.
     *
     * @return Response
     */
    public function create()
    {
        return view('dwsync::dw_entity_types.create');
    }

    /**
     * Store a newly created DwEntityType in storage.
     *
     * @param CreateDwEntityTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateDwEntityTypeRequest $request)
    {
        $input = $request->all();

        $dwEntityType = $this->dwEntityTypeRepository->create($input);

        Flash::success('Dw Entity Type saved successfully.');

        return redirect(route('dwsync.dwEntityTypes.index'));
    }

    /**
     * Display the specified DwEntityType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $dwEntityType = $this->dwEntityTypeRepository->findWithoutFail($id);

        if (empty($dwEntityType)) {
            Flash::error('Dw Entity Type not found');

            return redirect(route('dwsync.dwEntityTypes.index'));
        }

        return view('dwsync::dw_entity_types.show')->with('dwEntityType', $dwEntityType);
    }

    /**
     * Show the form for editing the specified DwEntityType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $dwEntityType = $this->dwEntityTypeRepository->findWithoutFail($id);

        if (empty($dwEntityType)) {
            Flash::error('Dw Entity Type not found');

            return redirect(route('dwsync.dwEntityTypes.index'));
        }

        return view('dwsync::dw_entity_types.edit')->with('dwEntityType', $dwEntityType);
    }

    /**
     * Update the specified DwEntityType in storage.
     *
     * @param  int              $id
     * @param UpdateDwEntityTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDwEntityTypeRequest $request)
    {
        $dwEntityType = $this->dwEntityTypeRepository->findWithoutFail($id);

        if (empty($dwEntityType)) {
            Flash::error('Dw Entity Type not found');

            return redirect(route('dwsync.dwEntityTypes.index'));
        }

        $dwEntityType = $this->dwEntityTypeRepository->update($request->all(), $id);

        Flash::success('Dw Entity Type updated successfully.');

        return redirect(route('dwsync.dwEntityTypes.index'));
    }

    /**
     * Remove the specified DwEntityType from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $dwEntityType = $this->dwEntityTypeRepository->findWithoutFail($id);

        if (empty($dwEntityType)) {
            Flash::error('Dw Entity Type not found');

            return redirect(route('dwsync.dwEntityTypes.index'));
        }

        $this->dwEntityTypeRepository->delete($id);

        Flash::success('Dw Entity Type deleted successfully.');

        return redirect(route('dwsync.dwEntityTypes.index'));
    }
}
