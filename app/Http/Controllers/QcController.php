<?php

namespace App\Http\Controllers;

use App\DataTables\QcDataTable;
use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use App\Repositories\QcRepository;
use App\Http\Requests\CreateQcRequest;
use App\Models\Obra;
use App\Models\Carteira;
use App\Models\Topologia;

class QcController extends AppBaseController
{
    /** @var  QcRepository */
    private $qcRepository;

    public function __construct(QcRepository $carteirasSlaRepo)
    {
        $this->qcRepository = $carteirasSlaRepo;
    }

    /**
     * Display a listing of the Carteiras Sla.
     *
     * @param QcDataTable $qcDataTable
     * @return Response
     */
    public function index( QcDataTable $qcDataTable) {
        return $qcDataTable->render(
            'qc.index'
        );
    }

    /**
     * Show the form for creating a new Carteira Sla.
     *
     * @return Response
     */
    public function create()
    {
        $obras = Obra::pluck('nome','id')->toArray();
        $carteiras = Carteira::pluck('nome','id')->toArray();
        $topologias = Topologia::pluck('nome','id')->toArray();

        return view('qc.create', compact('obras', 'carteiras', 'topologias'));
    }

    /**
     * Store a newly created CarteirasSla in storage.
     *
     * @param CreateQcRequest $request
     *
     * @return Response
     */
    public function store(CreateQcRequest $request)
    {
        $input = $request->all();

        $qc = $this->qcRepository->create($input);

        Flash::success('QC '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('qc.index'));
    }

    /**
     * Display the specified Qc.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $qc = $this->qcRepository->findWithoutFail($id);

        if (empty($qc)) {
            Flash::error('Qc '.trans('common.not-found'));

            return redirect(route('qc.index'));
        }

        return view('qc.show')->with('qc', $qc);
    }

    /**
     * Show the form for editing the specified Qc.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $qc = $this->qcRepository->findWithoutFail($id);
        $obras = Obra::pluck('nome','id')->toArray();
        $carteiras = Carteira::pluck('nome','id')->toArray();
        $topologias = Topologia::pluck('nome','id')->toArray();

        if (empty($qc)) {
            Flash::error('Qc '.trans('common.not-found'));

            return redirect(route('qc.index'));
        }

        return view('qc.edit', compact('qc', 'obras', 'carteiras', 'topologias'));
    }

    /**
     * Update the specified Grupo in storage.
     *
     * @param  int              $id
     * @param UpdateGrupoRequest $request
     *
     * @return Response
     */
    public function update($id, CreateQcRequest $request)
    {
        $qc = $this->qcRepository->findWithoutFail($id);

        if (empty($qc)) {
            Flash::error('Qc '.trans('common.not-found'));

            return redirect(route('qc.index'));
        }

        $qc = $this->qcRepository->update($request->all(), $id);

        Flash::success('Grupo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('qc.index'));
    }

    /**
     * Remove the specified Qc from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $qc = $this->qcRepository->findWithoutFail($id);

        if (empty($qc)) {
            Flash::error('Regional '.trans('common.not-found'));

            return redirect(route('regionals.index'));
        }

        $this->qcRepository->delete($id);

        Flash::success('Regional '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('qc.index'));
    }
}
