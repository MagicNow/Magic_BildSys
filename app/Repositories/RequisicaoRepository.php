<?php

namespace App\Repositories;

use App\Models\Requisicao;
use App\Models\RequisicaoItem;
use App\Models\RequisicaoItemLog;
use App\Models\RequisicaoLog;
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

        DB::beginTransaction();

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

            DB::rollback();
            throw $e;
        }

        DB::commit();

        $this->updateRequisicaoItem($request);

        return $requisicao;
    }


    public function updateRequisicaoItem(array $input) {

        DB::beginTransaction();

        try {

            $insumos = json_decode($input['insumos']);

            foreach ($insumos as $insumo) {

                if ($insumo->qtde > 0) {

                    $item = RequisicaoItem::find($insumo->id);

                    RequisicaoItemLog::create([
                        'requisicao_itens_id' => $item->id,
                        'qtde_anterior' => $item->qtde,
                        'qtde_nova' => $insumo->qtde,
                        'status_id_anterior' => '',
                        'status_id_novo' => '',
                        'user_id' => auth()->id(),
                    ]);

                    $item->save([
                        'qtde' => $insumo->qtde,
                    ]);
                }
            }

            $comodos = json_decode($input['comodos']);

            foreach ($comodos as $comodo) {

                if ($comodo->qtde > 0) {

                    $item = RequisicaoItem::find($comodo->id);

                    RequisicaoItemLog::create([
                        'requisicao_itens_id' => $item->id,
                        'qtde_anterior' => $item->qtde,
                        'qtde_nova' => $comodo->qtde,
                        'status_id_anterior' => '',
                        'status_id_novo' => '',
                        'user_id' => auth()->id(),
                    ]);

                    $item->save([
                        'qtde' => $comodo->qtde,
                    ]);
                }
            }

        } catch (Exception $e) {

            DB::rollback();
            throw $e;
        }

        DB::commit();

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
}
