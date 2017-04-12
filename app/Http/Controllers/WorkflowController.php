<?php

namespace App\Http\Controllers;

use App\Repositories\WorkflowAprovacaoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{
    public function aprovaReprova(Request $request)
    {
        $this->validate($request, [
            'id'=>'required|numeric',
            'tipo'=>'required',
            'resposta'=>'required|boolean',
            'motivo_id'=>'numeric',
            'justificativa'=>'required_with:motivo_id'
        ]);

        $aprovaReprova = WorkflowAprovacaoRepository::aprovaReprovaItem($request->tipo,
            $request->id, Auth::user(),
            $request->resposta,
            $request->motivo_id,
            $request->justificativa);

        return response()->json(['success'=>$aprovaReprova,'resposta'=>$request->resposta]);
    }
}
