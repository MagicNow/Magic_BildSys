<?php

namespace App\Http\Controllers;

use App\DataTables\CarteirasSlaDataTable;
use App\DataTables\CarteirasSlaItemDataTable;
use Illuminate\Http\Request;

use App\Repositories\CarteirasSlaRepository;

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
        return view('carterias_sla.create');
    }
}
