<?php

namespace App\Http\Controllers;

use App\DataTables\MedicaoDataTable;
use App\DataTables\MedicaoServicoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMedicaoServicoRequest;
use App\Http\Requests\UpdateMedicaoServicoRequest;
use App\Repositories\MedicaoServicoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class MedicaoServicoController extends AppBaseController
{
    /** @var  MedicaoServicoRepository */
    private $medicaoServicoRepository;

    public function __construct(MedicaoServicoRepository $medicaoServicoRepo)
    {
        $this->medicaoServicoRepository = $medicaoServicoRepo;
    }

    /**
     * Display a listing of the MedicaoServico.
     *
     * @param MedicaoServicoDataTable $medicaoServicoDataTable
     * @return Response
     */
    public function index(MedicaoServicoDataTable $medicaoServicoDataTable)
    {
        return $medicaoServicoDataTable->render('medicao_servicos.index');
    }

    /**
     * Show the form for creating a new MedicaoServico.
     *
     * @return Response
     */
    public function create()
    {
        return view('medicao_servicos.create');
    }

    /**
     * Store a newly created MedicaoServico in storage.
     *
     * @param CreateMedicaoServicoRequest $request
     *
     * @return Response
     */
    public function store(CreateMedicaoServicoRequest $request)
    {
        $input = $request->all();

        $medicaoServico = $this->medicaoServicoRepository->create($input);

        Flash::success('Medicao Servico '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('medicaoServicos.index'));
    }

    /**
     * Display the specified MedicaoServico.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(MedicaoDataTable $medicaoDataTable,$id)
    {
        $medicaoServico = $this->medicaoServicoRepository->findWithoutFail($id);

        if (empty($medicaoServico)) {
            Flash::error('Medicao Servico '.trans('common.not-found'));

            return redirect(route('medicaoServicos.index'));
        }

        return $medicaoDataTable->servico($id)->render('medicao_servicos.show',compact('medicaoServico'));
    }

    /**
     * Show the form for editing the specified MedicaoServico.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(MedicaoDataTable $medicaoDataTable,$id)
    {
        $medicaoServico = $this->medicaoServicoRepository->findWithoutFail($id);

        if (empty($medicaoServico)) {
            Flash::error('Medicao Servico '.trans('common.not-found'));

            return redirect(route('medicaoServicos.index'));
        }
        return $medicaoDataTable->servico($id)->render('medicao_servicos.edit',compact('medicaoServico'));
    }

    /**
     * Update the specified MedicaoServico in storage.
     *
     * @param  int              $id
     * @param UpdateMedicaoServicoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMedicaoServicoRequest $request)
    {
        $medicaoServico = $this->medicaoServicoRepository->findWithoutFail($id);

        if (empty($medicaoServico)) {
            Flash::error('Medicao Servico '.trans('common.not-found'));

            return redirect(route('medicaoServicos.index'));
        }
        $input = $request->all();
        $medicaoServico = $this->medicaoServicoRepository->update($input, $id);

        Flash::success('Medição do Serviço atualizada '.trans('common.successfully').'.');

        return redirect(route('medicoes.index'));
    }

    /**
     * Remove the specified MedicaoServico from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $medicaoServico = $this->medicaoServicoRepository->findWithoutFail($id);

        if (empty($medicaoServico)) {
            Flash::error('Medicao Servico '.trans('common.not-found'));

            return redirect(route('medicaoServicos.index'));
        }

        $this->medicaoServicoRepository->delete($id);

        Flash::success('Medicao Servico '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('medicaoServicos.index'));
    }
}
