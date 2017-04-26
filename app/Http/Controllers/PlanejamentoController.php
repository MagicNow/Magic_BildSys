<?php

namespace App\Http\Controllers;


use App\Models\Lembrete;
use App\Models\Planejamento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanejamentoController extends AppBaseController
{
    public function lembretes(Request $request){
        $lembretes = Lembrete::join('insumo_grupos','insumo_grupos.id','=','lembretes.insumo_grupo_id')
            ->join('insumos','insumos.insumo_grupo_id','=','insumo_grupos.id')
            ->join('planejamento_compras','planejamento_compras.insumo_id','=','insumos.id')
            ->join('planejamentos', 'planejamentos.id','=','planejamento_compras.planejamento_id')
            ->join('obras', 'obras.id','=','planejamentos.obra_id')
            ->join('obra_users', 'obra_users.obra_id','=','obras.id')
            ->whereNull('planejamentos.deleted_at')
            ->where('obra_users.user_id',Auth::user()->id)
            ->select([
                'lembretes.id',
                DB::raw("CONCAT(obras.nome,' - ',planejamentos.tarefa,' - ', lembretes.nome) title"),
                DB::raw("'event-info' as class"),
                DB::raw("CONCAT('/compras/obrasInsumos?planejamento_id=',planejamentos.id,'&insumo_grupos_id=',insumo_grupos.id) as url"),
                DB::raw("DATE_FORMAT(DATE_SUB(planejamentos.data, INTERVAL lembretes.`dias_prazo_minimo` DAY),'%d/%m/%Y') as inicio"),
                DB::raw("UNIX_TIMESTAMP(DATE_SUB(planejamentos.data, INTERVAL lembretes.`dias_prazo_minimo` DAY))*1000 as start"),
                DB::raw("UNIX_TIMESTAMP(DATE_SUB(planejamentos.data, INTERVAL lembretes.`dias_prazo_minimo` DAY))*1000 as end"),
            ]);

            if($request->from || $request->to){
                if($request->from){
                    $from = date('Y-m-d',$request->from/1000);
                    $lembretes->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL lembretes.`dias_prazo_minimo` DAY)'), '>=', $from);
                }
                if($request->to){
                    $to = date('Y-m-d',$request->to/1000);
                    $lembretes->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL lembretes.`dias_prazo_minimo` DAY)'), '<=', $to);
                }
            }else{
                $lembretes->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL lembretes.`dias_prazo_minimo` DAY)'), '<=',DB::raw('CURRENT_DATE'));
            }

            if($request->obra_id){
                $lembretes->where('planejamentos.obra_id', $request->obra_id);
            }
            $lembretes = $lembretes->distinct()->get();

        return response()->json([
            'success'=>true,
            'result'=>$lembretes
        ]);
    }

    public function getPlanejamentosByObra(Request $request)
    {
        $planejamentos = Planejamento::join('planejamento_compras','planejamento_compras.planejamento_id','=', 'planejamentos.id')
            ->where('obra_id', $request->obra_id)
            ->select([
                'planejamentos.id',
                'planejamentos.tarefa as text'
            ])->groupBy('planejamentos.id')->get();
        return response()->json($planejamentos);
    }
}