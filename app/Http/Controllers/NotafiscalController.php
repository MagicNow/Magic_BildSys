<?php

namespace App\Http\Controllers;

use App\DataTables\NotafiscalDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateNotafiscalRequest;
use App\Http\Requests\UpdateNotafiscalRequest;
use App\Models\Contrato;
use App\Repositories\NotafiscalRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Response;

class NotafiscalController extends AppBaseController
{
    /** @var  NotafiscalRepository */
    private $notafiscalRepository;

    public function __construct(NotafiscalRepository $notafiscalRepo)
    {
        $this->notafiscalRepository = $notafiscalRepo;
    }

    /**
     * Display a listing of the Notafiscal.
     *
     * @param NotafiscalDataTable $notafiscalDataTable
     * @return Response
     */
    public function index(NotafiscalDataTable $notafiscalDataTable)
    {
        return $notafiscalDataTable->render('notafiscals.index');
    }

    /**
     * Show the form for creating a new Notafiscal.
     *
     * @return Response
     */
    public function create()
    {
        $contrato = Contrato::select([
            'contratos.id',
            DB::raw("CONCAT('Contrato: ', contratos.id, ' - ','Fornecedor: ', fornecedores.nome) as nome")
            ])
            ->join('fornecedores','fornecedores.id', '=', 'contratos.fornecedor_id')
            ->pluck('nome','contratos.id')->toArray();
        return view('notafiscals.create', compact('contrato'));
    }

    /**
     * Store a newly created Notafiscal in storage.
     *
     * @param CreateNotafiscalRequest $request
     *
     * @return Response
     */
    public function store(CreateNotafiscalRequest $request)
    {
        $input = $request->all();

        $notafiscal = $this->notafiscalRepository->create($input);

        Flash::success('Notafiscal '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('notafiscals.index'));
    }

    /**
     * Display the specified Notafiscal.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal '.trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        return view('notafiscals.show')->with('notafiscal', $notafiscal);
    }

    /**
     * Show the form for editing the specified Notafiscal.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal '.trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        return view('notafiscals.edit')->with('notafiscal', $notafiscal);
    }

    /**
     * Update the specified Notafiscal in storage.
     *
     * @param  int              $id
     * @param UpdateNotafiscalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNotafiscalRequest $request)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal '.trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        $notafiscal = $this->notafiscalRepository->update($request->all(), $id);

        Flash::success('Notafiscal '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('notafiscals.index'));
    }

    /**
     * Remove the specified Notafiscal from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal '.trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        $this->notafiscalRepository->delete($id);

        Flash::success('Notafiscal '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('notafiscals.index'));
    }
}
