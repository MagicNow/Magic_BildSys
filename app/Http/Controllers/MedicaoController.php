<?php

namespace App\Http\Controllers;

use App\DataTables\MedicaoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMedicaoRequest;
use App\Http\Requests\UpdateMedicaoRequest;
use App\Models\Obra;
use App\Repositories\Admin\ObraRepository;
use App\Repositories\MedicaoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class MedicaoController extends AppBaseController
{
    /** @var  MedicaoRepository */
    private $medicaoRepository;

    public function __construct(MedicaoRepository $medicaoRepo)
    {
        $this->medicaoRepository = $medicaoRepo;
    }

    /**
     * Display a listing of the Medicao.
     *
     * @param MedicaoDataTable $medicaoDataTable
     * @return Response
     */
    public function index(MedicaoDataTable $medicaoDataTable)
    {
        return $medicaoDataTable->render('medicoes.index');
    }

    public function preCreate()
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

        return view('medicoes.pre-create', compact('obras'));
    }

    /**
     * Show the form for creating a new Medicao.
     *
     * @return Response
     */
    public function create()
    {
        return view('medicoes.create');
    }

    /**
     * Store a newly created Medicao in storage.
     *
     * @param CreateMedicaoRequest $request
     *
     * @return Response
     */
    public function store(CreateMedicaoRequest $request)
    {
        $input = $request->all();

        $medicao = $this->medicaoRepository->create($input);

        Flash::success('Medicao '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('medicoes.index'));
    }

    /**
     * Display the specified Medicao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $medicao = $this->medicaoRepository->findWithoutFail($id);

        if (empty($medicao)) {
            Flash::error('Medicao '.trans('common.not-found'));

            return redirect(route('medicoes.index'));
        }

        return view('medicoes.show')->with('medicao', $medicao);
    }

    /**
     * Show the form for editing the specified Medicao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $medicao = $this->medicaoRepository->findWithoutFail($id);

        if (empty($medicao)) {
            Flash::error('Medicao '.trans('common.not-found'));

            return redirect(route('medicoes.index'));
        }

        return view('medicoes.edit')->with('medicao', $medicao);
    }

    /**
     * Update the specified Medicao in storage.
     *
     * @param  int              $id
     * @param UpdateMedicaoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMedicaoRequest $request)
    {
        $medicao = $this->medicaoRepository->findWithoutFail($id);

        if (empty($medicao)) {
            Flash::error('Medicao '.trans('common.not-found'));

            return redirect(route('medicoes.index'));
        }

        $medicao = $this->medicaoRepository->update($request->all(), $id);

        Flash::success('Medicao '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('medicoes.index'));
    }

    /**
     * Remove the specified Medicao from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $medicao = $this->medicaoRepository->findWithoutFail($id);

        if (empty($medicao)) {
            Flash::error('Medicao '.trans('common.not-found'));

            return redirect(route('medicoes.index'));
        }

        $this->medicaoRepository->delete($id);

        Flash::success('Medicao '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('medicoes.index'));
    }
}
