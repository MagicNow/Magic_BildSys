<?php

namespace App\Http\Controllers;

use App\Models\WorkflowTipo;
use App\Repositories\WorkflowAprovacaoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkflowAlcada;
use App\Models\WorkflowAprovacao;

class WorkflowController extends Controller
{
    public function aprovaReprova(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'tipo' => 'required',
            'resposta' => 'required|boolean',
            'motivo_id' => 'numeric',
            'justificativa' => 'required_with:motivo_id'
        ]);

        $aprovaReprova = WorkflowAprovacaoRepository::aprovaReprovaItem(
            $request->tipo,
            $request->id,
            Auth::user(),
            $request->resposta,
            $request->motivo_id,
            $request->justificativa
        );

        return response()->json([
            'success' => $aprovaReprova,
            'resposta' => $request->resposta
        ]);
    }

    public function aprovaReprovaTudo(Request $request)
    {
        $this->validate($request, [
            'tipo' => 'required',
            'resposta' => 'required|boolean',
            'motivo_id' => 'numeric',
            'justificativa' => 'required_with:motivo_id',
            'pai' => 'required|numeric',
            'pai_tipo' => 'required',
            'filhos_relacionamento' => 'required'
        ]);

        eval('$objPai = \\App\\Models\\' . $request->pai_tipo . '::find(' . $request->pai . ');');
        if (!$objPai) {
            return response()->json(['success' => false, 'resposta' => 'Erro ao encontrar objeto pai']);
        }
        eval('$itens = $objPai->' . $request->filhos_relacionamento . '()->get();');

        if (!$itens) {
            return response()->json(['success' => false, 'resposta' => 'Erro ao encontrar itens filhos']);
        }
        $aprovadosReprovados = 0;
        foreach ($itens as $item) {
            $aprovaReprova = WorkflowAprovacaoRepository::aprovaReprovaItem($request->tipo,
                $item->id, Auth::user(),
                $request->resposta,
                $request->motivo_id,
                $request->justificativa);

            if($aprovaReprova){
                $aprovadosReprovados += 1;
            }
        }

        if($aprovadosReprovados == 0){
            return response()->json(['success' => false, 'resposta' => 'Já não havia nenhum item à aprovar!', 'refresh' =>true ]);
        }

        return response()->json(['success' => true]);

    }

    public function redefinir(Request $request)
    {
        $tipo = WorkflowTipo::qualTipo($request->workflowTipo);
        eval('$obj = \\App\\Models\\' . $tipo . '::find(' . $request->id . ');');
        $obj->touch();
        $obj->colocaEmAprovacao();
        return back();
    }

    public function detalhes(Request $request)
    {
        $tipo = WorkflowTipo::qualTipo($request->workflowTipo);
        eval('$obj = \\App\\Models\\' . $tipo . '::find(' . $request->id . ');');

        $alcadas_aprovacao = [
            'atuais'=>[],
            'historicos'=>[]
        ];
        $dataUltimoPeriodo = null;
        $aprovadores = WorkflowAprovacaoRepository::verificaUsuariosAprovadores(
            WorkflowTipo::find($request->workflowTipo), $obj->qualObra(),
            null,[ $request->id => $request->id ], $tipo
        );
        $dataUltimoPeriodo = $obj->dataUltimoPeriodoAprovacao();
        if($aprovadores){
            foreach ($aprovadores as $alcada){
                $aprovacoes = WorkflowAprovacao::where('aprovavel_id', $request->id)
                    ->whereHas('workflowAlcada',function ($query) use($request){
                        $query->where('workflow_tipo_id', $request->workflowTipo);
                    })
                    ->where('workflow_alcada_id',$alcada['alcada']->id)
                    ->orderBy('created_at')
                    ->with('user');

                // Busca apenas aprovações após a última alteração
                if($dataUltimoPeriodo){
                    $aprovacoes->where('created_at','>', $dataUltimoPeriodo->format('Y-m-d H:i:s'));
                }
                $aprovacoes = $aprovacoes->get();

                $hoje = \Carbon\Carbon::create();
                if($dataUltimoPeriodo) {
                    $data_maxima = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$dataUltimoPeriodo)->addDays($alcada['alcada_prazo']);
                    $dias_restantes = $hoje->diffInDays($data_maxima, false);
                }else{
                    $data_maxima = $hoje->addDays($alcada['alcada_prazo']);
                    $dias_restantes = $alcada['alcada_prazo'];
                }

                $alcadas_aprovacao['atuais'][$alcada['alcada']->id] = [
                    'alcada'=>$alcada['alcada'],
                    'prazo'=>$dias_restantes,
                    'data_maxima'=>$data_maxima->format('d/m/Y H:i'),
                    'itens'=>[],
                    'falta'=>(isset($alcada['users']) ? $alcada['users'] : [])
                ];

                foreach ($aprovacoes as $aprovacao) {
                    $alcadas_aprovacao['atuais'][$alcada['alcada']->id]['itens'][] = $aprovacao;
                    unset($alcadas_aprovacao['atuais'][$alcada['alcada']->id]['falta'][$aprovacao->user_id]);
                }
            }


            // Busca apenas aprovações após a última alteração
            if($dataUltimoPeriodo) {
                $aprovacoes = WorkflowAprovacao::where('aprovavel_id', $request->id)
                    ->whereHas('workflowAlcada', function ($query) use ($request) {
                        $query->where('workflow_tipo_id', $request->workflowTipo);
                    })
                    ->orderBy('created_at')
                    ->with('workflowAlcada', 'user', 'workflowAlcada.workflowUsuarios')
                    ->where('created_at', '<=', $dataUltimoPeriodo->format('Y-m-d H:i:s'))
                    ->get();
                if($aprovacoes->count()){
                    $aprovacao = $aprovacoes->first();
                    $alcada_atual = $aprovacao->workflow_alcada_id;
                    $alcada_count = 0;
                    $alcadas_aprovacao['historicos'][$alcada_count] = [
                        'alcada' => $aprovacao->workflowAlcada,
                        'itens' => [],
                        'falta' => $aprovacao->workflowAlcada->workflowUsuarios()->pluck('name', 'users.id')->toArray()
                    ];
                    foreach ($aprovacoes as $aprovacao) {
                        if ($alcada_atual != $aprovacao->workflow_alcada_id) {
                            $alcada_atual = $aprovacao->workflow_alcada_id;
                            $alcada_count++;
                            $alcadas_aprovacao['historicos'][$alcada_count] = [
                                'alcada' => $aprovacao->workflowAlcada,
                                'itens' => [],
                                'falta' => $aprovacao->workflowAlcada->workflowUsuarios()->pluck('name', 'users.id')->toArray()
                            ];
                        }
                        $alcadas_aprovacao['historicos'][$alcada_count]['itens'][] = $aprovacao;
                        unset($alcadas_aprovacao['historicos'][$alcada_count]['falta'][$aprovacao->user_id]);
                    }
                }

            }
        }
        $workflow_tipo_id = $request->workflowTipo;
        $id = $request->id;


        return view('workflow.detalhes', compact('alcadas_aprovacao','dataUltimoPeriodo','id','workflow_tipo_id'));
    }
}
