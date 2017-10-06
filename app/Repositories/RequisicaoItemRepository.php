<?php

namespace App\Repositories;

use App\Models\Requisicao;
use App\Models\RequisicaoItem;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Common\BaseRepository;

class RequisicaoItemRepository extends BaseRepository
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
        return RequisicaoItem::class;
    }

    /*
     * Pega os itens de uma requisição e retorna um HTML de uma tabela com as informações dos itens e botões para ações
     */

    public function getRequisicaoItens($id) {

        $r = DB::table('requisicao_itens as ri');
        $r->select(DB::raw(
            'i.nome insumo, 
            i.id insumo_id, 
            i.unidade_sigla , 
            sum(ri.qtde) qtde_requisicao ,
            e.qtde estoque, 
            e.id estoque_id ,
            IF(ri.comodo <> " ","true","false") as temComodo,
            ri.apartamento,
            ri.comodo,
            ri.id'));

        $r->leftJoin('requisicao as r','ri.requisicao_id', '=', 'r.id');
        $r->leftJoin('estoque as e','ri.estoque_id', '=', 'e.id');
        $r->leftJoin('insumos as i','e.insumo_id', '=', 'i.id');
        $r->where('ri.requisicao_id',$id);

        $r->groupBy('ri.estoque_id');
        $r->orderBy('i.nome');

        $insumos = $r->get();

        $requisicao = Requisicao::where('id',$id)->first();


        $html = '';

        foreach ($insumos as $insumo) {

            $qtde_usada = $this->getTotalUtilizadoByInsumo($requisicao, $insumo);

            $previsto = $this->getTotalPrevistoByInsummo($requisicao, $insumo);

            $qtde_disponivel = $previsto - $qtde_usada;

            $html .= '<tr>';
            $html .= '<td>' . $insumo->insumo . '</td>';
            $html .= '<td>' . $insumo->unidade_sigla . '</td>';
            $html .= '<td id="previsto-' . $insumo->insumo_id . '">' . $previsto . '</td>';
            $html .= '<td id="disponivel-' . $insumo->insumo_id . '">' . $qtde_disponivel . '</td>';
            $html .= '<td id="estoque-' . $insumo->insumo_id . '">' . $insumo->estoque . '</td>';
            $html .= '<td><input type="number" min="0" step=".01" class="form-control js-input-qtde" value="' . $insumo->qtde_requisicao . '" name="' . $insumo->insumo_id . '" id="' . $insumo->insumo_id . '" data-estoque="' . $insumo->estoque_id . '"  data-id="' . $insumo->id . '"></td>';
            $html .= '<td>status</td>';


            if ($insumo->temComodo == 'true') {

                $html .= '<td><button type="button" class="btn btn-primary js-btn-modal-comodo" data-id="' . $insumo->insumo_id . '" >
                    Detalhar
                </button></td>';

            } else {

                $html .= '<td></td>';
            }

            $html .= '<td><button type="button" class="btn btn-primary" data-id="' . $insumo->insumo_id . '" >
                    Imprimir
                </button></td>';

            $html .= '</tr>';
        }

        return $html;
    }


    public function getInsumosRequisicaoByComodo ($requisicao) {

        $r = DB::table('requisicao_itens as ri');
        $r->select(
            DB::raw(
            'i.id insumo_id,
            r.obra_id,
            ri.*')
        );

        $r->leftJoin('requisicao as r','ri.requisicao_id', '=', 'r.id');
        $r->leftJoin('estoque as e','ri.estoque_id', '=', 'e.id');
        $r->leftJoin('insumos as i','e.insumo_id', '=', 'i.id');

        $r->where('ri.apartamento', '<>', '');

        $r->where('ri.requisicao_id',$requisicao);

        $r->orderBy('i.nome');

        $insumos = $r->get();

        $html = '';

        foreach ($insumos as $insumo) {

            $levantamento_id = $this->getInsumoIdByLevantamento($insumo);

            $html .= '<input type="hidden" name="hidden['.$insumo->insumo_id.'][]" value="' . $insumo->qtde . '" data-apartamento="' . $insumo->apartamento . '" data-comodo="' . $insumo->comodo . '" id="' . $insumo->id . '" data-levantamento="' . $levantamento_id->levantamento_id . '">';
        }

        return $html;
    }


    /*
     * Pega o ID da tabela LEVANTAMENTOS para poder referenciar a nível de comodo a quantidade quando uma requisição esta em edição.
     */

    private function getInsumoIdByLevantamento ($requisicao) {

        $r = DB::table('levantamentos as l');

        $r->select(DB::raw('l.id levantamento_id'));
        $r->leftJoin('insumos as i','i.id', '=', 'l.insumo');
        $r->leftJoin('estoque as e','e.insumo_id', '=', 'l.insumo');
        $r->where('l.obra_id',$requisicao->obra_id);
        $r->where('l.torre',$requisicao->torre);
        $r->where('l.pavimento',$requisicao->pavimento);
        $r->where('l.insumo',$requisicao->insumo_id);
        $r->where('l.andar',$requisicao->andar);
        $r->where('l.apartamento',$requisicao->apartamento);
        $r->where('l.comodo',$requisicao->comodo);

        return $r->first();
    }


    /*
     * Consulta a tabela de ESTOQUE TRANSAÇÃO para determinar qual a quantidade de um determinado insumo já foi utilizada usando os seguintes filtros:
     * Obra
     * Torre
     * Pavimento
     * Andar
     * Trecho
     * Apartamento ( usado se a consulta for a nível de apartamento )
     * Comodo ( usado se a consulta for a nível de apartamento )
     */

    private function getTotalUtilizadoByInsumo($requisicao, $insumo,$comodo = false) {

        $r = DB::table('estoque as e');

        $r->select(DB::raw('sum(et.qtde) qtde_usada'));

        $r->leftJoin('estoque_transacao as et','e.id', '=', 'et.estoque_id');
        $r->leftJoin('requisicao as r','r.id', '=', 'et.requisicao_id');
        $r->leftJoin('requisicao_itens as ri','ri.requisicao_id', '=', 'r.id');

        $r->where('e.obra_id',$requisicao->obra);
        $r->where('ri.torre',$requisicao->torre);
        $r->where('ri.pavimento',$requisicao->pavimento);
        $r->where('e.insumo_id',$insumo->insumo_id);
        $r->where('et.tipo','S');

        if (!empty($requisicao->andar))
            $r->where('ri.andar',$requisicao->andar);

        if (!empty($requisicao->trecho))
            $r->where('ri.trecho',$requisicao->trecho);

        if ($comodo) {

            $r->where('apartamento', $insumo->apartamento);
            $r->where('comodo', $insumo->comodo);
        }

        $total = $r->first();

        return $total->qtde_usada;
    }

    /*
     * Consulta na tabela de LEVANTAMENTOS a quantidade prevista para um determinado insumo usando os seguintes filtros:
     * Obra
     * Torre
     * Pavimento
     * Andar
     * Trecho
     */

    private function getTotalPrevistoByInsummo($requisicao,$insumo) {


        $r = DB::table('levantamentos as l');

        $r->select(DB::raw('sum(l.quantidade) previsto'));
        $r->leftJoin('insumos as i','i.id', '=', 'l.insumo');
        $r->leftJoin('estoque as e','e.insumo_id', '=', 'l.insumo');

        $r->where('l.obra_id',$requisicao->obra_id);
        $r->where('l.torre',$requisicao->torre);
        $r->where('l.pavimento',$requisicao->pavimento);

        if (!empty($requisicao->andar))
            $r->where('l.andar',$requisicao->andar);

        if (!empty($requisicao->trecho))
            $r->where('l.trecho',$requisicao->trecho);

        $r->where('l.insumo', $insumo->insumo_id);

        $r->groupBy('l.insumo');
        $r->orderBy('l.insumo');

        $insumos = $r->first();

        return $insumos->previsto;
    }

}
