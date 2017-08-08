<?php

namespace App\Http\Controllers;

use App\DataTables\RegionalDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateRegionalRequest;
use App\Http\Requests\UpdateRegionalRequest;
use App\Repositories\RegionalRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class RegionalController extends AppBaseController
{
    /** @var  RegionalRepository */
    private $regionalRepository;

    public function __construct(RegionalRepository $regionalRepo)
    {
        $this->regionalRepository = $regionalRepo;
    }

    /**
     * Display a listing of the Regional.
     *
     * @param RegionalDataTable $regionalDataTable
     * @return Response
     */
    public function index(RegionalDataTable $regionalDataTable)
    {
        return $regionalDataTable->render('regionals.index');
    }

    /**
     * Show the form for creating a new Regional.
     *
     * @return Response
     */
    public function create()
    {
        return view('regionals.create');
    }

    /**
     * Store a newly created Regional in storage.
     *
     * @param CreateRegionalRequest $request
     *
     * @return Response
     */
    public function store(CreateRegionalRequest $request)
    {
        $input = $request->all();

        $regional = $this->regionalRepository->create($input);

        Flash::success('Regional '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('regionals.index'));
    }

    /**
     * Display the specified Regional.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $regional = $this->regionalRepository->findWithoutFail($id);

        if (empty($regional)) {
            Flash::error('Regional '.trans('common.not-found'));

            return redirect(route('regionals.index'));
        }

        return view('regionals.show')->with('regional', $regional);
    }

    /**
     * Show the form for editing the specified Regional.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $regional = $this->regionalRepository->findWithoutFail($id);

        if (empty($regional)) {
            Flash::error('Regional '.trans('common.not-found'));

            return redirect(route('regionals.index'));
        }

        return view('regionals.edit')->with('regional', $regional);
    }

    /**
     * Update the specified Regional in storage.
     *
     * @param  int              $id
     * @param UpdateRegionalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRegionalRequest $request)
    {
        $regional = $this->regionalRepository->findWithoutFail($id);

        if (empty($regional)) {
            Flash::error('Regional '.trans('common.not-found'));

            return redirect(route('regionals.index'));
        }

        $regional = $this->regionalRepository->update($request->all(), $id);

        Flash::success('Regional '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('regionals.index'));
    }

    /**
     * Remove the specified Regional from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $regional = $this->regionalRepository->findWithoutFail($id);

        if (empty($regional)) {
            Flash::error('Regional '.trans('common.not-found'));

            return redirect(route('regionals.index'));
        }

        $this->regionalRepository->delete($id);

        Flash::success('Regional '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('regionals.index'));
    }
}
