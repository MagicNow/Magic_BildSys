<?php

namespace App\Http\Controllers;

use App\DataTables\QuadroDeConcorrenciaItemDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateQuadroDeConcorrenciaItemRequest;
use App\Http\Requests\UpdateQuadroDeConcorrenciaItemRequest;
use App\Repositories\QuadroDeConcorrenciaItemRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class QuadroDeConcorrenciaItemController extends AppBaseController
{
    /** @var  QuadroDeConcorrenciaItemRepository */
    private $quadroDeConcorrenciaItemRepository;

    public function __construct(QuadroDeConcorrenciaItemRepository $quadroDeConcorrenciaItemRepo)
    {
        $this->quadroDeConcorrenciaItemRepository = $quadroDeConcorrenciaItemRepo;
    }

    /**
     * Display a listing of the QuadroDeConcorrenciaItem.
     *
     * @param QuadroDeConcorrenciaItemDataTable $quadroDeConcorrenciaItemDataTable
     * @return Response
     */
    public function index(QuadroDeConcorrenciaItemDataTable $quadroDeConcorrenciaItemDataTable)
    {
        return $quadroDeConcorrenciaItemDataTable->render('quadro_de_concorrencia_items.index');
    }

    /**
     * Show the form for creating a new QuadroDeConcorrenciaItem.
     *
     * @return Response
     */
    public function create()
    {
        return view('quadro_de_concorrencia_items.create');
    }

    /**
     * Store a newly created QuadroDeConcorrenciaItem in storage.
     *
     * @param CreateQuadroDeConcorrenciaItemRequest $request
     *
     * @return Response
     */
    public function store(CreateQuadroDeConcorrenciaItemRequest $request)
    {
        $input = $request->all();

        $quadroDeConcorrenciaItem = $this->quadroDeConcorrenciaItemRepository->create($input);

        Flash::success('Quadro De Concorrencia Item '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('quadroDeConcorrenciaItems.index'));
    }

    /**
     * Display the specified QuadroDeConcorrenciaItem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $quadroDeConcorrenciaItem = $this->quadroDeConcorrenciaItemRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrenciaItem)) {
            Flash::error('Quadro De Concorrencia Item '.trans('common.not-found'));

            return redirect(route('quadroDeConcorrenciaItems.index'));
        }

        return view('quadro_de_concorrencia_items.show')->with('quadroDeConcorrenciaItem', $quadroDeConcorrenciaItem);
    }

    /**
     * Show the form for editing the specified QuadroDeConcorrenciaItem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $quadroDeConcorrenciaItem = $this->quadroDeConcorrenciaItemRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrenciaItem)) {
            Flash::error('Quadro De Concorrencia Item '.trans('common.not-found'));

            return redirect(route('quadroDeConcorrenciaItems.index'));
        }

        return view('quadro_de_concorrencia_items.edit')->with('quadroDeConcorrenciaItem', $quadroDeConcorrenciaItem);
    }

    /**
     * Update the specified QuadroDeConcorrenciaItem in storage.
     *
     * @param  int              $id
     * @param UpdateQuadroDeConcorrenciaItemRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQuadroDeConcorrenciaItemRequest $request)
    {
        $quadroDeConcorrenciaItem = $this->quadroDeConcorrenciaItemRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrenciaItem)) {
            Flash::error('Quadro De Concorrencia Item '.trans('common.not-found'));

            return redirect(route('quadroDeConcorrenciaItems.index'));
        }

        $quadroDeConcorrenciaItem = $this->quadroDeConcorrenciaItemRepository->update($request->all(), $id);

        Flash::success('Quadro De Concorrencia Item '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('quadroDeConcorrenciaItems.index'));
    }

    /**
     * Remove the specified QuadroDeConcorrenciaItem from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $quadroDeConcorrenciaItem = $this->quadroDeConcorrenciaItemRepository->findWithoutFail($id);

        if (empty($quadroDeConcorrenciaItem)) {
            Flash::error('Quadro De Concorrencia Item '.trans('common.not-found'));

            return redirect(route('quadroDeConcorrenciaItems.index'));
        }

        $this->quadroDeConcorrenciaItemRepository->delete($id);

        Flash::success('Quadro De Concorrencia Item '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('quadroDeConcorrenciaItems.index'));
    }
}
