<?php

namespace App\Http\Controllers;

use App\DataTables\MascaraPadraoDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateMascaraPadraoRequest;
use App\Http\Requests\UpdateMascaraPadraoRequest;
use App\Models\MascaraPadraoInsumo;
use App\Models\MascaraPadrao;
use App\Models\Obra;
use App\Models\Insumo;
use App\Models\TipoOrcamento;
use App\Repositories\MascaraPadraoRepository;
use App\Repositories\CodeRepository;
//use App\Repositories\ImportacaoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;
use DB;

class MascaraPadraoController extends AppBaseController
{
    /** @var  MascaraPadraoRepository */
    private $mascaraPadraoRepository;

    public function __construct(MascaraPadraoRepository $mascaraPadraoRepo)
    {
        $this->mascaraPadraoRepository = $mascaraPadraoRepo;
    }

    /**
     * Display a listing of the MascaraPadrao.
     *
     * @param MascaraPadraoDataTable $mascaraPadraoDataTable
     * @return Response
     */
    public function index(MascaraPadraoDataTable $mascaraPadraoDataTable)
    {
        return $mascaraPadraoDataTable->render('mascara_padrao.index');
    }

    /**
     * Show the form for creating a new MascaraPadrao.
     *
     * @return Response
     */
    public function create()
    {
        $obras = Obra::pluck('nome','id')->toArray();
		$orcamento_tipos = TipoOrcamento::pluck('nome','id')->toArray();

        return view('mascara_padrao.create', compact('orcamento_tipos','obras'));
    }

    /**
     * Store a newly created MascaraPadrao in storage.
     *
     * @param CreateMascaraPadraoRequest $request
     *
     * @return Response
     */
    public function store(CreateMascaraPadraoRequest $request)
    {
        if (count($request->contratoInsumos)) {
            foreach ($request->contratoInsumos as $item) {
                if ($item['insumo_id'] != '' && floatval($item['valor_unitario']) > 0 ) {
                    $contrato_insumo = new MascaraPadraoInsumo();
                    $contrato_insumo->catalogo_contrato_id = $mascaraPadrao->id;
                    $contrato_insumo->insumo_id = $item['insumo_id'];
                    $contrato_insumo->valor_unitario = $item['valor_unitario'] ? money_to_float($item['valor_unitario']) : 0;
                    $contrato_insumo->pedido_minimo = $item['pedido_minimo'];
                    $contrato_insumo->pedido_multiplo_de = $item['pedido_multiplo_de'];
                    $contrato_insumo->periodo_inicio = $item['periodo_inicio'];
                    $contrato_insumo->periodo_termino = $item['periodo_termino'];
                    $contrato_insumo->user_id = auth()->id();
                    $contrato_insumo = $mascaraPadrao->contratoInsumos()->save($contrato_insumo);

                }
            }
        }
        
        Flash::success('Mascara Padrao '.trans('common.saved').' '.trans('common.successfully').'.');
        
        return redirect(route('mascara_padrao.index'));
    }

    /**
     * Display the specified MascaraPadrao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mascaraPadrao = $this->mascaraPadraoRepository->findWithoutFail($id);

        if (empty($mascaraPadrao)) {
            Flash::error('Mascara Padrao '.trans('common.not-found'));

            return redirect(route('mascara_padrao.index'));
        }

        return view('mascara_padrao.show')->with('mascaraPadrao', $mascaraPadrao);
    }

    /**
     * Show the form for editing the specified MascaraPadrao.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $mascaraPadrao = $this->mascaraPadraoRepository->findWithoutFail($id);

        if (empty($mascaraPadrao)) {
            Flash::error('Mascara Padrao '.trans('common.not-found'));

            return redirect(route('mascara_padrao.index'));
        }

        return view('mascara_padrao.edit', compact('mascaraPadrao'));
    }

    /**
     * Update the specified MascaraPadrao in storage.
     *
     * @param  int              $id
     * @param UpdateMascaraPadraoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMascaraPadraoRequest $request)
    {
        $mascaraPadrao = $this->mascaraPadraoRepository->findWithoutFail($id);

        if (empty($mascaraPadrao)) {
            Flash::error('Mascara Padrao '.trans('common.not-found'));

            return redirect(route('mascara_padrao.index'));
        }

        $input = $request->except('fornecedor_cod','contratoInsumos');

        // Template
        $campos_extras_minuta = [];
        if(isset($input['CAMPO_EXTRA_MINUTA'])){
            foreach ($input['CAMPO_EXTRA_MINUTA'] as $campo => $valor){
                $campos_extras_minuta[$campo] = $valor;
            }

            $campos_extras_minuta = json_encode($campos_extras_minuta);
        }else{
            $campos_extras_minuta = null;
        }
        $input['campos_extras_minuta'] = $campos_extras_minuta;
        // Contrato
        $campos_extras_contrato = [];
        if(isset($input['CAMPO_EXTRA_CONTRATO'])){
            foreach ($input['CAMPO_EXTRA_CONTRATO'] as $campo => $valor){
                $campos_extras_contrato[$campo] = $valor;
            }
            $campos_extras_contrato = json_encode($campos_extras_contrato);
        }else{
            $campos_extras_contrato = null;
        }
        $input['campos_extras_contrato'] = $campos_extras_contrato;

        $mascaraPadrao = $this->mascaraPadraoRepository->update($input, $id);


        if($request->regional){
            foreach ($request->regional as $regional_id){
                $mascaraPadraoRegional = MascaraPadraoRegional::where('regional_id',$regional_id)->where('catalogo_contrato_id', $mascaraPadrao->id)->first();
                if(!$mascaraPadraoRegional){
                    $mascaraPadraoRegional = MascaraPadraoRegional::create([
                        'catalogo_contrato_id' => $mascaraPadrao->id,
                        'regional_id' => $regional_id,
                        'user_id' => auth()->id(),
                        'catalogo_contrato_status_id' => 2
                    ]);
                }
            }
        }

        $alteraStatusParaValidacao = false;
        if (count($request->contratoInsumos)) {
            foreach ($request->contratoInsumos as $item) {
                if ($item['insumo_id'] != '' && floatval($item['valor_unitario']) > 0 ) {
                    if(!isset($item['id'])){
                        $contrato_insumo = new MascaraPadraoInsumo();
                        $contrato_insumo->catalogo_contrato_id = $mascaraPadrao->id;
                        $contrato_insumo->insumo_id = $item['insumo_id'];
                        $contrato_insumo->valor_unitario = $item['valor_unitario'] ? money_to_float($item['valor_unitario']) : 0;
                        $contrato_insumo->pedido_minimo = $item['pedido_minimo'];
                        $contrato_insumo->pedido_multiplo_de = $item['pedido_multiplo_de'];
                        $contrato_insumo->periodo_inicio = $item['periodo_inicio'];
                        $contrato_insumo->periodo_termino = $item['periodo_termino'];
                        $contrato_insumo->user_id = auth()->id();
                        $contrato_insumo = $mascaraPadrao->contratoInsumos()->save($contrato_insumo);

                        $logCatInsumo = new MascaraPadraoInsumoLog();
                        $logCatInsumo->contrato_insumo_id = $contrato_insumo->id;
                        $logCatInsumo->user_id = auth()->id();
                        $logCatInsumo->valor_unitario = $contrato_insumo->valor_unitario;
                        $logCatInsumo->pedido_minimo = $contrato_insumo->pedido_minimo;
                        $logCatInsumo->pedido_multiplo_de = $contrato_insumo->pedido_multiplo_de;
                        $logCatInsumo->periodo_inicio = $contrato_insumo->periodo_inicio;
                        $logCatInsumo->periodo_termino = $contrato_insumo->periodo_termino;
                        $logCatInsumo->save();

                        $alteraStatusParaValidacao = true;

                    }else{
                        if($mascaraPadrao->catalogo_contrato_status_id != 3){
                            $contrato_insumo = MascaraPadraoInsumo::find($item['id']);
                            $logCatInsumo = new MascaraPadraoInsumoLog();
                            $logCatInsumo->contrato_insumo_id = $contrato_insumo->id;
                            $logCatInsumo->user_id = auth()->id();
                            $logCatInsumo->valor_unitario_anterior = $contrato_insumo->valor_unitario;
                            $logCatInsumo->pedido_minimo_anterior = $contrato_insumo->pedido_minimo;
                            $logCatInsumo->pedido_multiplo_de_anterior = $contrato_insumo->pedido_multiplo_de;
                            $logCatInsumo->periodo_inicio_anterior = $contrato_insumo->periodo_inicio;
                            $logCatInsumo->periodo_termino_anterior = $contrato_insumo->periodo_termino;
                            $logCatInsumo->save();

                            $contrato_insumo->insumo_id = $item['insumo_id'];
                            $contrato_insumo->valor_unitario = $item['valor_unitario'] ? money_to_float($item['valor_unitario']) : 0;
                            $contrato_insumo->pedido_minimo = $item['pedido_minimo'];
                            $contrato_insumo->pedido_multiplo_de = $item['pedido_multiplo_de'];
                            $contrato_insumo->periodo_inicio = $item['periodo_inicio'];
                            $contrato_insumo->periodo_termino = $item['periodo_termino'];
                            $contrato_insumo->user_id = auth()->id();

                            $contrato_insumo->update();

                            $logCatInsumo->valor_unitario = $contrato_insumo->valor_unitario;
                            $logCatInsumo->pedido_minimo = $contrato_insumo->pedido_minimo;
                            $logCatInsumo->pedido_multiplo_de = $contrato_insumo->pedido_multiplo_de;
                            $logCatInsumo->periodo_inicio = $contrato_insumo->periodo_inicio;
                            $logCatInsumo->periodo_termino = $contrato_insumo->periodo_termino;
                            $logCatInsumo->save();

                            $alteraStatusParaValidacao = true;

                        }


                    }

                }
            }
        }

        if(count($request->reajuste)) {
            foreach ($request->reajuste as $reajuste) {
                if ($reajuste['insumo_id'] != ''
                    && floatval($reajuste['valor_unitario']) > 0
                    && floatval($reajuste['pedido_minimo']) > 0
                    && floatval($reajuste['pedido_multiplo_de']) > 0
                    && strlen($reajuste['periodo_inicio']) > 0
                    && strlen($reajuste['periodo_termino']) > 0
                ) {
                    $contrato_insumo = new MascaraPadraoInsumo();
                    $contrato_insumo->catalogo_contrato_id = $mascaraPadrao->id;
                    $contrato_insumo->insumo_id = $reajuste['insumo_id'];
                    $contrato_insumo->valor_unitario = $reajuste['valor_unitario'] ? money_to_float($reajuste['valor_unitario']) : 0;
                    $contrato_insumo->pedido_minimo = $reajuste['pedido_minimo'];
                    $contrato_insumo->pedido_multiplo_de = $reajuste['pedido_multiplo_de'];
                    $contrato_insumo->periodo_inicio = $reajuste['periodo_inicio'];
                    $contrato_insumo->periodo_termino = $reajuste['periodo_termino'];
                    $contrato_insumo->user_id = auth()->id();
                    $contrato_insumo->save();

                    $logCatInsumo = new MascaraPadraoInsumoLog();
                    $logCatInsumo->contrato_insumo_id = $contrato_insumo->id;
                    $logCatInsumo->user_id = auth()->id();
                    $logCatInsumo->valor_unitario_anterior = $contrato_insumo->valor_unitario;
                    $logCatInsumo->pedido_minimo_anterior = $contrato_insumo->pedido_minimo;
                    $logCatInsumo->pedido_multiplo_de_anterior = $contrato_insumo->pedido_multiplo_de;
                    $logCatInsumo->periodo_inicio_anterior = $contrato_insumo->periodo_inicio;
                    $logCatInsumo->periodo_termino_anterior = $contrato_insumo->periodo_termino;

                    $logCatInsumo->valor_unitario = $contrato_insumo->valor_unitario;
                    $logCatInsumo->pedido_minimo = $contrato_insumo->pedido_minimo;
                    $logCatInsumo->pedido_multiplo_de = $contrato_insumo->pedido_multiplo_de;
                    $logCatInsumo->periodo_inicio = $contrato_insumo->periodo_inicio;
                    $logCatInsumo->periodo_termino = $contrato_insumo->periodo_termino;
                    $logCatInsumo->save();

                    $alteraStatusParaValidacao = true;
                }
            }
        }

        if($request->gerar_minuta){
            // Status do acordo
            $mascaraPadrao->catalogo_contrato_status_id = 2;
            $mascaraPadrao->save();

            foreach ($mascaraPadrao->regionais()->where('catalogo_contrato_status_id',3)->get() as $mascaraPadraoRegional){
                $mascaraPadraoRegional->catalogo_contrato_status_id = 2;
                $mascaraPadraoRegional->save();
                $mascaraPadraoRegional = MascaraPadraoRegionalLog::create([
                    'catalogo_contrato_regional_id' => $mascaraPadraoRegional->id,
                    'catalogo_contrato_status_id' => $mascaraPadraoRegional->catalogo_contrato_status_id
                ]);
            }

            $mascaraPadraoStatus = MascaraPadraoStatusLog::create([
                'catalogo_contrato_id' => $mascaraPadrao->id,
                'catalogo_contrato_status_id' => 2,
                'user_id' => auth()->id()
            ]);
            Flash::success('Mascara Padrao '.trans('common.updated').' e minuta disponível para baixar.');
        }else{

            if( $alteraStatusParaValidacao ){
                // Status do acordo
                $mascaraPadrao->catalogo_contrato_status_id = 2;
                $mascaraPadrao->save();

                foreach ($mascaraPadrao->regionais()->where('catalogo_contrato_status_id',3)->get() as $mascaraPadraoRegional){
                    $mascaraPadraoRegional->catalogo_contrato_status_id = 2;
                    $mascaraPadraoRegional->save();
                    $mascaraPadraoRegional = MascaraPadraoRegionalLog::create([
                        'catalogo_contrato_regional_id' => $mascaraPadraoRegional->id,
                        'catalogo_contrato_status_id' => $mascaraPadraoRegional->catalogo_contrato_status_id
                    ]);
                }
            }

            if ($request->minuta_assinada) {
                $destinationPath = CodeRepository::saveFile($request->minuta_assinada, 'acordos/assinado_' . $mascaraPadrao->id);

                $mascaraPadrao->minuta_assinada = $destinationPath;
                $mascaraPadrao->save();

                $mascaraPadrao->catalogo_contrato_status_id = 3;
                $mascaraPadrao->save();
                MascaraPadraoStatusLog::create([
                    'catalogo_contrato_id' => $mascaraPadrao->id,
                    'catalogo_contrato_status_id' => $mascaraPadrao->catalogo_contrato_status_id,
                    'user_id' => auth()->id()
                ]);
                foreach ($mascaraPadrao->regionais()->whereIn('catalogo_contrato_status_id',[1,2])->get() as $mascaraPadraoObra){

                    $mascaraPadraoObra->catalogo_contrato_status_id = $mascaraPadrao->catalogo_contrato_status_id;
                    $mascaraPadraoObra->save();
                    $mascaraPadraoRegional = MascaraPadraoRegionalLog::create([
                        'catalogo_contrato_regional_id' => $mascaraPadraoObra->id,
                        'catalogo_contrato_status_id' => $mascaraPadraoObra->catalogo_contrato_status_id
                    ]);
                }
                if($mascaraPadrao->fornecedor->faltaDados()){
                    $mascaraPadrao->catalogo_contrato_status_id = 4;
                    $mascaraPadrao->save();
                    MascaraPadraoStatusLog::create([
                        'catalogo_contrato_id' => $mascaraPadrao->id,
                        'catalogo_contrato_status_id' => $mascaraPadrao->catalogo_contrato_status_id,
                        'user_id' => auth()->id()
                    ]);
                    $acao = 'Arquivo enviado, porém não foi possível ativar, visto que o fornecedor possui dados incompletos';
                }else{
                    $acao = 'Arquivo enviado e Acordo ativado!';
                }


                Flash::success($acao);
            }else{
                Flash::success('Mascara Padrao '.trans('common.updated').' '.trans('common.successfully').'.');
            }



        }
		
        return redirect(route('mascara_padrao.index'));
    }

    /**
     * Remove the specified MascaraPadrao from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mascaraPadrao = $this->mascaraPadraoRepository->findWithoutFail($id);

        if (empty($mascaraPadrao)) {
            Flash::error('Mascara Padrao '.trans('common.not-found'));

            return redirect(route('mascara_padrao.index'));
        }

        $this->mascaraPadraoRepository->delete($id);

        Flash::success('Mascara Padrao '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('mascara_padrao.index'));
    }

    public function deleteInsumo(Request $request)
    {
        try {
            $acao = false;
            $mensagem = "Ocorreu um erro ao deletar o insumo";

            $catalogo_contrato_insumo = MascaraPadraoInsumo::find($request->insumo);

            $insumos = MascaraPadraoInsumo::where('insumo_id', $catalogo_contrato_insumo->insumo_id)->get();

            if ($insumos) {
                foreach ($insumos as $insumo){
                    $acao = $insumo->delete();
                    $mensagem = "Insumo deletado com sucesso";
                }
            }

            return response()->json(['sucesso' => $acao, 'resposta' => $mensagem, 'insumo_id' => $catalogo_contrato_insumo->insumo_id]);
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
    
}
