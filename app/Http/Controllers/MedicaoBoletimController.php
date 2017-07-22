<?php

namespace App\Http\Controllers;

use App\DataTables\MedicaoBoletimDataTable;
use App\DataTables\MedicaoServicoDataTable;
use App\DataTables\Scopes\MedicaoServicoScope;
use App\Http\Requests;
use App\Http\Requests\CreateMedicaoBoletimRequest;
use App\Http\Requests\UpdateMedicaoBoletimRequest;
use App\Models\Obra;
use App\Repositories\MedicaoBoletimRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class MedicaoBoletimController extends AppBaseController
{
    /** @var  MedicaoBoletimRepository */
    private $medicaoBoletimRepository;

    public function __construct(MedicaoBoletimRepository $medicaoBoletimRepo)
    {
        $this->medicaoBoletimRepository = $medicaoBoletimRepo;
    }

    /**
     * Display a listing of the MedicaoBoletim.
     *
     * @param MedicaoBoletimDataTable $medicaoBoletimDataTable
     * @return Response
     */
    public function index(MedicaoBoletimDataTable $medicaoBoletimDataTable)
    {
        return $medicaoBoletimDataTable->render('medicao_boletims.index');
    }

    /**
     * Show the form for creating a new MedicaoBoletim.
     *
     * @return Response
     */
    public function create(MedicaoServicoDataTable $medicaoServicoDataTable)
    {
        $obras = Obra::whereHas('users', function($query){
            $query->where('user_id', auth()->id());
        })
            ->whereRaw('EXISTS (SELECT 1 FROM mc_medicao_previsoes 
                    JOIN obra_torres ON obra_torre_id = obra_torres.id
                    WHERE obras.id = obra_torres.obra_id
                     LIMIT 1)')
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();
        return $medicaoServicoDataTable->addScope(new MedicaoServicoScope())->render('medicao_boletims.create', compact('obras'));
    }

    /**
     * Store a newly created MedicaoBoletim in storage.
     *
     * @param CreateMedicaoBoletimRequest $request
     *
     * @return Response
     */
    public function store(CreateMedicaoBoletimRequest $request)
    {
        $input = $request->all();
        
        $medicaoBoletim = $this->medicaoBoletimRepository->create($input);

        Flash::success('Boletim de Medição '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('boletim-medicao.index'));
    }

    /**
     * Display the specified MedicaoBoletim.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $medicaoBoletim = $this->medicaoBoletimRepository->findWithoutFail($id);

        if (empty($medicaoBoletim)) {
            Flash::error('Medicao Boletim '.trans('common.not-found'));

            return redirect(route('boletim-medicao.index'));
        }

        return view('medicao_boletims.show')->with('medicaoBoletim', $medicaoBoletim);
    }

    /**
     * Show the form for editing the specified MedicaoBoletim.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $medicaoBoletim = $this->medicaoBoletimRepository->findWithoutFail($id);

        if (empty($medicaoBoletim)) {
            Flash::error('Medicao Boletim '.trans('common.not-found'));

            return redirect(route('boletim-medicao.index'));
        }

        return view('medicao_boletims.edit')->with('medicaoBoletim', $medicaoBoletim);
    }

    /**
     * Update the specified MedicaoBoletim in storage.
     *
     * @param  int              $id
     * @param UpdateMedicaoBoletimRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMedicaoBoletimRequest $request)
    {
        $medicaoBoletim = $this->medicaoBoletimRepository->findWithoutFail($id);

        if (empty($medicaoBoletim)) {
            Flash::error('Medicao Boletim '.trans('common.not-found'));

            return redirect(route('boletim-medicao.index'));
        }

        $medicaoBoletim = $this->medicaoBoletimRepository->update($request->all(), $id);

        Flash::success('Medicao Boletim '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('boletim-medicao.index'));
    }

    /**
     * Remove the specified MedicaoBoletim from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $medicaoBoletim = $this->medicaoBoletimRepository->findWithoutFail($id);

        if (empty($medicaoBoletim)) {
            Flash::error('Medicao Boletim '.trans('common.not-found'));

            return redirect(route('boletim-medicao.index'));
        }

        $this->medicaoBoletimRepository->delete($id);

        Flash::success('Medicao Boletim '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('boletim-medicao.index'));
    }
}
