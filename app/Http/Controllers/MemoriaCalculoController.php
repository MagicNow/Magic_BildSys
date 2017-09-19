<?php

namespace App\Http\Controllers;

use App\DataTables\MemoriaCalculoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMemoriaCalculoRequest;
use App\Http\Requests\UpdateMemoriaCalculoRequest;
use App\Repositories\Admin\ObraRepository;
use App\Repositories\MemoriaCalculoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Response;

class MemoriaCalculoController extends AppBaseController
{
    /** @var  MemoriaCalculoRepository */
    private $memoriaCalculoRepository;

    public function __construct(MemoriaCalculoRepository $memoriaCalculoRepo)
    {
        $this->memoriaCalculoRepository = $memoriaCalculoRepo;
    }

    /**
     * Display a listing of the MemoriaCalculo.
     *
     * @param MemoriaCalculoDataTable $memoriaCalculoDataTable
     * @return Response
     */
    public function index(MemoriaCalculoDataTable $memoriaCalculoDataTable)
    {
        return $memoriaCalculoDataTable->render('memoria_calculos.index');
    }

    /**
     * Show the form for creating a new MemoriaCalculo.
     *
     * @return Response
     */
    public function create(ObraRepository $obraRepository)
    {
        $obras = $obraRepository
            ->findByUser(auth()->id())
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();
        
        return view('memoria_calculos.create', compact('obras'));
    }

    /**
     * Store a newly created MemoriaCalculo in storage.
     *
     * @param CreateMemoriaCalculoRequest $request
     *
     * @return Response
     */
    public function store(CreateMemoriaCalculoRequest $request)
    {
        $input = $request->all();
        
        $memoriaCalculo = $this->memoriaCalculoRepository->create($input);

        Flash::success('Memoria Calculo '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('memoriaCalculos.index'));
    }

    /**
     * Display the specified MemoriaCalculo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $memoriaCalculo = $this->memoriaCalculoRepository->findWithoutFail($id);

        if (empty($memoriaCalculo)) {
            Flash::error('Memoria Calculo '.trans('common.not-found'));

            return redirect(route('memoriaCalculos.index'));
        }

        $blocos = $memoriaCalculo->blocosEstruturados(false);

        return view('memoria_calculos.show', compact('blocos','memoriaCalculo'));
    }

    /**
     * Show the form for editing the specified MemoriaCalculo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id, ObraRepository $obraRepository)
    {
        $obras = $obraRepository
            ->findByUser(auth()->id())
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        $memoriaCalculo = $this->memoriaCalculoRepository->findWithoutFail($id);

        if (empty($memoriaCalculo)) {
            Flash::error('Memoria Calculo '.trans('common.not-found'));

            return redirect(route('memoriaCalculos.index'));
        }
        $blocos = $memoriaCalculo->blocosEstruturados();

        return view('memoria_calculos.edit', compact('obras','memoriaCalculo','blocos'));
    }

    public function clonar($id, ObraRepository $obraRepository)
    {
        $obras = $obraRepository
            ->findByUser(auth()->id())
            ->pluck('nome', 'id')
            ->prepend('', '')
            ->toArray();

        $memoriaCalculo = $this->memoriaCalculoRepository->findWithoutFail($id);

        if (empty($memoriaCalculo)) {
            Flash::error('Memoria Calculo '.trans('common.not-found'));

            return redirect(route('memoriaCalculos.index'));
        }
        $blocos = $memoriaCalculo->blocosEstruturados(true);

        $clonando = 1;

        return view('memoria_calculos.create', compact('obras','memoriaCalculo','blocos','clonando'));
    }

    /**
     * Update the specified MemoriaCalculo in storage.
     *
     * @param  int              $id
     * @param UpdateMemoriaCalculoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMemoriaCalculoRequest $request)
    {
        $memoriaCalculo = $this->memoriaCalculoRepository->findWithoutFail($id);

        if (empty($memoriaCalculo)) {
            Flash::error('Memoria Calculo '.trans('common.not-found'));

            return redirect(route('memoriaCalculos.index'));
        }

        $memoriaCalculo = $this->memoriaCalculoRepository->update($request->all(), $id);

        Flash::success('Memoria Calculo '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('memoriaCalculos.index'));
    }

    /**
     * Remove the specified MemoriaCalculo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $memoriaCalculo = $this->memoriaCalculoRepository->findWithoutFail($id);

        if (empty($memoriaCalculo)) {
            Flash::error('Memoria Calculo '.trans('common.not-found'));

            return redirect(route('memoriaCalculos.index'));
        }

        $this->memoriaCalculoRepository->delete($id);

        Flash::success('Memoria Calculo '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('memoriaCalculos.index'));
    }

    public function putSessionMemoriaDeCalculo(Request $request)
    {
        $array_session = Session::get('previsao-de-memoria-de-calculo-'.$request->contrato_id.'-'.$request->contrato_item_apropriacao_id) ? : [];

        Session::put(
            'previsao-de-memoria-de-calculo-'.$request->contrato_id.'-'.$request->contrato_item_apropriacao_id,
            array_replace(
                $array_session, [$request->memoria_calculo_bloco_id =>
                    [
                        'contrato_id' => $request->contrato_id,
                        'contrato_item_apropriacao_id' => $request->contrato_item_apropriacao_id,
                        'memoria_calculo_bloco_id' => $request->memoria_calculo_bloco_id,
                        'estrutura' => $request->estrutura,
                        'pavimento' => $request->pavimento,
                        'trecho' => $request->trecho,
                        'estrutura_id' => $request->estrutura_id,
                        'data' => $request->data,
                        'quantidade' => $request->quantidade,
                        'planejamento_id' => $request->planejamento_id
                    ]
                ]
            )
        );
        
        return response()->json(true);
    }
}
