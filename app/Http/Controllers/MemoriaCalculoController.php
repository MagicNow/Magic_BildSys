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

        return view('memoria_calculos.show')->with('memoriaCalculo', $memoriaCalculo);
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
        $blocos = [];
        $memoriaBlocos = $memoriaCalculo->blocos;
        if(count($memoriaBlocos)){
            $estruturas = [];
            $pavimentos = [];
            $trechos = [];
            foreach ($memoriaBlocos as $memoriaBloco) {
                if(!isset($estruturas[$memoriaBloco->estrutura])){
                    $estruturas[$memoriaBloco->estrutura] = [
                        'id'=>   $memoriaBloco->estrutura,
                        'nome'=> $memoriaBloco->estruturaObj->nome,
                        'ordem' => $memoriaBloco->ordem,
                        'itens' => []
                    ];
                }

                if(!isset($pavimentos[$memoriaBloco->estrutura][$memoriaBloco->pavimento])){
                    $pavimentos[$memoriaBloco->estrutura][$memoriaBloco->pavimento] = [
                        'id'=>   $memoriaBloco->pavimento,
                        'nome'=> $memoriaBloco->pavimentoObj->nome,
                        'ordem' => count($pavimentos),
                        'estrutura' => $memoriaBloco->estrutura,
                        'itens' => []
                    ];
                }

                if(!isset($trechos[$memoriaBloco->estrutura][$memoriaBloco->pavimento][$memoriaBloco->trecho])){
                    $trechos[$memoriaBloco->estrutura][$memoriaBloco->pavimento][$memoriaBloco->trecho] = [
                        'id'=>   $memoriaBloco->trecho,
                        'nome'=> $memoriaBloco->trechoObj->nome,
                        'ordem' => count($trechos),
                        'estrutura' => $memoriaBloco->estrutura,
                        'pavimento' => $memoriaBloco->pavimento
                    ];
                }

            }
            // organiza a array
            foreach ($trechos as $estrutura_id => $estruturaTrechos){
                foreach ($estruturaTrechos as $pavimento_id => $pavimentoTrechos) {
                    foreach ($pavimentoTrechos as $trecho) {
                        $pavimentos[$trecho['estrutura']][$trecho['pavimento']]['itens'][$trecho['ordem']] = $trecho;
                    }
                }

            }

            foreach ($pavimentos as $estrutura_id => $pavimentos_internos){
                foreach ($pavimentos_internos as $pavimento_interno){
                    $estruturas[$pavimento_interno['estrutura']]['itens'][$pavimento_interno['ordem']] = $pavimento_interno;
                }
            }

            foreach ($estruturas as $estrutura){
                $blocos[$estrutura['ordem']] = $estrutura;
            }

        }
        ksort($blocos);
        
        return view('memoria_calculos.edit', compact('obras','memoriaCalculo','blocos'));
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
}
