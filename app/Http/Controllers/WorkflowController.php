<?php

namespace App\Http\Controllers;

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

    public function detalhes(Request $request)
    {
        $alcadas = WorkflowAlcada::where('workflow_tipo_id', $request->workflowTipo)
            ->orderBy('ordem')
            ->get();

        $alcada = $alcadas->where('ordem', $request->alcada)->first();

        $aprovacoes = WorkflowAprovacao::where('workflow_alcada_id', $alcada->id)
            ->where('aprovavel_id', $request->id)
            ->orderBy('created_at')
            ->get();

        return view('workflow.detalhes', compact('aprovacoes', 'alcada'));
    }
}
