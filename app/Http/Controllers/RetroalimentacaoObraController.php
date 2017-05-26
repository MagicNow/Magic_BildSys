<?php

namespace App\Http\Controllers;

use App\DataTables\RetroalimentacaoObraDataTable;
use App\Models\Obra;
use App\Http\Requests;
use App\Http\Requests\CreateRetroalimentacaoObraRequest;
use App\Http\Requests\UpdateRetroalimentacaoObraRequest;
use App\Repositories\RetroalimentacaoObraRepository;
use Flash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AppBaseController;
use Response;

class RetroalimentacaoObraController extends AppBaseController
{
    /** @var  RetroalimentacaoObraRepository */
    private $retroalimentacaoObraRepository;

    public function __construct(RetroalimentacaoObraRepository $retroalimentacaoObraRepo)
    {
        $this->retroalimentacaoObraRepository = $retroalimentacaoObraRepo;
    }

    /**
     * Display a listing of the RetroalimentacaoObra.
     *
     * @param RetroalimentacaoObraDataTable $retroalimentacaoObraDataTable
     * @return Response
     */
    public function index(RetroalimentacaoObraDataTable $retroalimentacaoObraDataTable)
    {
        return $retroalimentacaoObraDataTable->render('retroalimentacao_obras.index');
    }

    /**
     * Show the form for creating a new RetroalimentacaoObra.
     *
     * @return Response
     */
    public function create()
    {
        $obras = Obra::pluck('nome','id')->toArray();
        return view('retroalimentacao_obras.create', compact('obras'));
    }
    
    /**
     * Show the form for creating a new RetroalimentacaoObra.
     *
     * @return Response
     */
    public function create_front()
    {
        $obras = Obra::pluck('nome','id')->toArray();
        return view('retroalimentacao_obras.create_front', compact('obras'));
    }

    /**
     * Store a newly created RetroalimentacaoObra in storage.
     *
     * @param CreateRetroalimentacaoObraRequest $request
     *
     * @return Response
     */
    public function store(CreateRetroalimentacaoObraRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();

        $retroalimentacaoObra = $this->retroalimentacaoObraRepository->create($input);

        Flash::success('Retroalimentação inserida com sucesso.');

        return redirect($request->origem);
    }

    /**
     * Display the specified RetroalimentacaoObra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $retroalimentacaoObra = $this->retroalimentacaoObraRepository->findWithoutFail($id);

        if (empty($retroalimentacaoObra)) {
            Flash::error('Retroalimentacao Obra '.trans('common.not-found'));

            return redirect(route('retroalimentacaoObras.index'));
        }

        return view('retroalimentacao_obras.show')->with('retroalimentacaoObra', $retroalimentacaoObra);
    }

    /**
     * Show the form for editing the specified RetroalimentacaoObra.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $retroalimentacaoObra = $this->retroalimentacaoObraRepository->findWithoutFail($id);
        $obras = Obra::pluck('nome','id')->toArray();
        if (empty($retroalimentacaoObra)) {
            Flash::error('Retroalimentacao Obra '.trans('common.not-found'));

            return redirect(route('retroalimentacaoObras.index'));
        }

        return view('retroalimentacao_obras.edit',compact('retroalimentacaoObra', 'obras'));
    }

    /**
     * Update the specified RetroalimentacaoObra in storage.
     *
     * @param  int              $id
     * @param UpdateRetroalimentacaoObraRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRetroalimentacaoObraRequest $request)
    {
        $retroalimentacaoObra = $this->retroalimentacaoObraRepository->findWithoutFail($id);

        if (empty($retroalimentacaoObra)) {
            Flash::error('Retroalimentacao Obra '.trans('common.not-found'));

            return redirect(route('retroalimentacaoObras.index'));
        }
        $input = $request->all();
        if(isset($input['aceite'])){
            $input['aceite'] = 1;
        }
        $retroalimentacaoObra = $this->retroalimentacaoObraRepository->update($input, $id);
        Flash::success('Retroalimentacao Obra'.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('retroalimentacaoObras.index'));
    }

    /**
     * Remove the specified RetroalimentacaoObra from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $retroalimentacaoObra = $this->retroalimentacaoObraRepository->findWithoutFail($id);

        if (empty($retroalimentacaoObra)) {
            Flash::error('Retroalimentacao Obra '.trans('common.not-found'));

            return redirect(route('retroalimentacaoObras.index'));
        }

        $this->retroalimentacaoObraRepository->delete($id);

        Flash::success('Retroalimentacao Obra '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('retroalimentacaoObras.index'));
    }
}
