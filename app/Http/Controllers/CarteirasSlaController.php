<?php

namespace App\Http\Controllers;

use App\DataTables\CarteirasSlaDataTable;
use App\DataTables\CarteirasSlaItemDataTable;
use Illuminate\Http\Request;

use App\Repositories\CarteirasSlaRepository;
use App\Http\Requests\CreateCarteirasSlaRequest;

class CarteirasSlaController extends AppBaseController
{
    /** @var  CarteirasSlaRepository */
    private $carteirasSlaRepository;

    public function __construct(CarteirasSlaRepository $carteirasSlaRepo)
    {
        $this->carteirasSlaRepository = $carteirasSlaRepo;
    }

    /**
     * Display a listing of the Carteiras Sla.
     *
     * @param CarteirasSlaDataTable $carteirasSlaDataTable
     * @return Response
     */
    public function index( CarteirasSlaDataTable $carteirasSlaDataTable) {
        return $carteirasSlaDataTable->render(
            'carteiras_sla.index'
        );
    }

    /**
     * Show the form for creating a new Carteira Sla.
     *
     * @return Response
     */
    public function create()
    {
        return view('carteiras_sla.create');
    }

    /**
     * Store a newly created CarteirasSla in storage.
     *
     * @param CreateCarteirasSlaRequest $request
     *
     * @return Response
     */
    public function store(CreateCarteirasSlaRequest $request)
    {
        $input = $request->all();

        $medicaoServico = $this->carteirasSlaRepository->create($input);

        Flash::success('QC '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('carteiras_sla.index'));
    }
}
