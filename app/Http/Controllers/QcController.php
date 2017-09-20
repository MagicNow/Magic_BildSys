<?php

namespace App\Http\Controllers;

use App\DataTables\QcDataTable;
use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use App\Repositories\QcRepository;
use App\Http\Requests\CreateQcRequest;
use App\Models\Obra;

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
        return view('qc.create', compact('obras'));
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

        $medicaoServico = $this->qcRepository->create($input);

        Flash::success('QC '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('qc.index'));
    }
}
