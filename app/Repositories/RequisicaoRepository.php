<?php

namespace App\Repositories;

use App\Models\Requisicao;
use App\Models\RequisicaoItem;
use App\Models\RequisicaoItemLog;
use App\Models\RequisicaoLog;
use App\Models\RequisicaoStatus;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Common\BaseRepository;

class RequisicaoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'user_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Requisicao::class;
    }

    public function getRequisicao($id) {

        $requisicao = Requisicao::query()
            ->select([
                'requisicao.*',
                'users.name as usuario',
                'obras.nome as obra',
                'requisicao_status.nome as status',
            ])
            ->join('requisicao_status','requisicao_status.id','requisicao.status_id')
            ->join('obras','obras.id','requisicao.obra_id')
            ->join('users','users.id','requisicao.user_id')
            ->where('requisicao.id',$id);

        return $requisicao->first();
    }

    public function create(array $input) {

        try {

            $r = parent::create([
                'obra_id' => $input['obra_id'],
                'local' => $input['local'],
                'torre' => $input['torre'],
                'pavimento' => $input['pavimento'],
                'trecho' => $input['trecho'],
                'andar' => $input['andar'],
                'user_id' => auth()->id(),
                'status_id' => 1
            ]);

            $this->requisicaoItem($input, $r->id);

            return true;

        } catch (Exception $e) {

            throw $e;
        }

    }


    public function update(array $request, $id)
    {
        $requisicao = $this->find($id);

        try {

            RequisicaoLog::create([
                'requisicao_id' => $requisicao->id,
                'status_id_anterior' => $requisicao->status_id,
                'status_id_novo' => $request['status_id'],
                'user_id' => auth()->id(),
            ]);

            $requisicao->update([
                'status_id' => $request['status_id'],
            ]);

        } catch (Exception $e) {

            throw $e;
        }

        return $requisicao;
    }


    public function requisicaoItem(array $input, $requisicao_id) {

        $insumos = json_decode($input['insumos']);

        foreach ($insumos as $insumo) {

            if ($insumo->qtde > 0) {

                RequisicaoItem::create([
                    'requisicao_id' => $requisicao_id,
                    'estoque_id' => $insumo->estoque_id,
                    'unidade' => '',
                    'qtde' => $insumo->qtde,
                    'local' => $input['local'],
                    'torre' => $input['torre'],
                    'pavimento' => $input['pavimento'],
                    'trecho' => $input['trecho'],
                    'andar' => $input['andar'],
                ]);

            }
        }

        $comodos = json_decode($input['comodos']);

        foreach ($comodos as $comodo) {

            if ($comodo->qtde > 0) {

                RequisicaoItem::create([
                    'requisicao_id' => $requisicao_id,
                    'estoque_id' => $comodo->estoque_id,
                    'unidade' => '',
                    'qtde' => $comodo->qtde,
                    'local' => $input['local'],
                    'torre' => $input['torre'],
                    'pavimento' => $input['pavimento'],
                    'trecho' => $input['trecho'],
                    'andar' => $input['andar'],
                    'apartamento' => $comodo->apartamento,
                    'comodo' => $comodo->comodo,
                ]);

            }
        }

    }
    
    public static function verificaAplicacao($requisicao, $insumo_id, $qtd)
    {
        $array_status = [];
        $requisicao_status = RequisicaoStatus::NOVA;

        if(count($requisicao->requisicaoItens)) {
            foreach($requisicao->requisicaoItens as $item) {
                if($item->estoque->insumo_id == $insumo_id) {
                    if($item->qtde == $qtd) {
                        $item->status_id = RequisicaoStatus::APLICADO_TOTAL;
                    } else {
                        $item->status_id = RequisicaoStatus::APLICADO_PARCIAL;
                    }
                    $item->save();

                    array_push($array_status, $item->status_id);
                }
            }
        }

        if(count($array_status)) {
            foreach ($array_status as $status) {
                if($status == RequisicaoStatus::APLICADO_TOTAL) {
                    $requisicao_status = RequisicaoStatus::APLICADO_TOTAL;
                }

                if($status == RequisicaoStatus::APLICADO_PARCIAL) {
                    $requisicao_status = RequisicaoStatus::APLICADO_PARCIAL;
                    break;
                }
            }
        }

        if($requisicao_status) {
            $requisicao->status_id = $requisicao_status;
            $requisicao->save();
        }
    }
}
