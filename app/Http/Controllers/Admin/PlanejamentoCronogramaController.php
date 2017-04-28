<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PlanejamentoCronogramaDataTable;
use App\Http\Requests\Admin;
use App\Http\Controllers\AppBaseController;

class PlanejamentoCronogramaController extends AppBaseController
{
    /**
     * Display a listing of the PlanejamentoCronograma.
     *
     * @param PlanejamentoCronogramaDataTable $planejamentoCronogramaDataTable
     * @return Response
     */
    public function index(PlanejamentoCronogramaDataTable $planejamentoCronogramaDataTable)
    {
        return $planejamentoCronogramaDataTable->render('admin.planejamento_cronogramas.index');
    }
}
