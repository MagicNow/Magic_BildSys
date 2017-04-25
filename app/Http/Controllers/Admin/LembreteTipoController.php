<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\LembreteTipoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateLembreteTipoRequest;
use App\Http\Requests\Admin\UpdateLembreteTipoRequest;
use App\Repositories\Admin\LembreteTipoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class LembreteTipoController extends AppBaseController
{
    /** @var  LembreteTipoRepository */
    private $lembreteTipoRepository;

    public function __construct(LembreteTipoRepository $lembreteTipoRepo)
    {
        $this->lembreteTipoRepository = $lembreteTipoRepo;
    }

    /**
     * Display a listing of the LembreteTipo.
     *
     * @param LembreteTipoDataTable $lembreteTipoDataTable
     * @return Response
     */
    public function index(LembreteTipoDataTable $lembreteTipoDataTable)
    {
        return $lembreteTipoDataTable->render('admin.lembrete_tipos.index');
    }

    /**
     * Show the form for creating a new LembreteTipo.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.lembrete_tipos.create');
    }

    /**
     * Store a newly created LembreteTipo in storage.
     *
     * @param CreateLembreteTipoRequest $request
     *
     * @return Response
     */
    public function store(CreateLembreteTipoRequest $request)
    {
        $input = $request->all();

        $lembreteTipo = $this->lembreteTipoRepository->create($input);

        Flash::success('Lembrete Tipo '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.lembreteTipos.index'));
    }

    /**
     * Display the specified LembreteTipo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $lembreteTipo = $this->lembreteTipoRepository->findWithoutFail($id);

        if (empty($lembreteTipo)) {
            Flash::error('Lembrete Tipo '.trans('common.not-found'));

            return redirect(route('admin.lembreteTipos.index'));
        }

        return view('admin.lembrete_tipos.show')->with('lembreteTipo', $lembreteTipo);
    }

    /**
     * Show the form for editing the specified LembreteTipo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $lembreteTipo = $this->lembreteTipoRepository->findWithoutFail($id);

        if (empty($lembreteTipo)) {
            Flash::error('Lembrete Tipo '.trans('common.not-found'));

            return redirect(route('admin.lembreteTipos.index'));
        }

        return view('admin.lembrete_tipos.edit')->with('lembreteTipo', $lembreteTipo);
    }

    /**
     * Update the specified LembreteTipo in storage.
     *
     * @param  int              $id
     * @param UpdateLembreteTipoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLembreteTipoRequest $request)
    {
        $lembreteTipo = $this->lembreteTipoRepository->findWithoutFail($id);

        if (empty($lembreteTipo)) {
            Flash::error('Lembrete Tipo '.trans('common.not-found'));

            return redirect(route('admin.lembreteTipos.index'));
        }

        $lembreteTipo = $this->lembreteTipoRepository->update($request->all(), $id);

        Flash::success('Lembrete Tipo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.lembreteTipos.index'));
    }

    /**
     * Remove the specified LembreteTipo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $lembreteTipo = $this->lembreteTipoRepository->findWithoutFail($id);

        if (empty($lembreteTipo)) {
            Flash::error('Lembrete Tipo '.trans('common.not-found'));

            return redirect(route('admin.lembreteTipos.index'));
        }

        $this->lembreteTipoRepository->delete($id);

        Flash::success('Lembrete Tipo '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.lembreteTipos.index'));
    }
}
