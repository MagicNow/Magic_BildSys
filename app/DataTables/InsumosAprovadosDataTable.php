<?php

namespace App\DataTables;

use App\Models\OrdemDeCompraItem;
use App\User;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class InsumosAprovadosDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $query = $this->query();

        return $this->datatables
            ->eloquent($query)
            ->editColumn('action', 'ordem_de_compras.insumos_aprovados_datatables_actions')
            ->editColumn('codigo_insumo', function($obj){
                return "<strong  data-toggle=\"tooltip\" data-placement=\"top\" data-html=\"true\"
                         title=\"". $obj->grupo->codigo .' '. $obj->grupo->nome . ' <br> ' .
                                    $obj->subgrupo1->codigo .' '.$obj->subgrupo1->nome . ' <br> ' .
                                    $obj->subgrupo2->codigo .' '.$obj->subgrupo2->nome . ' <br> ' .
                                    $obj->subgrupo3->codigo .' '.$obj->subgrupo3->nome . ' <br> ' .
                                    $obj->servico->codigo .' '.$obj->servico->nome  ."\">
                     $obj->codigo_insumo
                </strong>";
            })
            ->editColumn('ordem_de_compra_id', function($obj){
                return '<a href="'.url('/ordens-de-compra/detalhes/'.$obj->ordem_de_compra_id).'">'.$obj->ordem_de_compra_id.'</a>';
            })
            ->editColumn('sla', function($obj){
                return $obj->sla.' <i class="fa fa-circle ' .($obj->sla < 0?'text-danger' : ( $obj->sla < 30 ? 'text-warning' : 'text-success') ).'" aria-hidden="true"></i>';
            })
            ->filterColumn('sla', function($query, $keyword){
                $query->whereRaw("(SELECT
                    DATEDIFF(
                        ADDDATE(
                            ordem_de_compra_itens.updated_at, ". //-- Data de início do Planejamento
                    "INTERVAL(
                                IFNULL(
                                (SELECT
                                    SUM(L.dias_prazo_minimo) prazo
                                FROM
                                    lembretes L
                                JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                WHERE
                                    EXISTS( ". //-- Busca apenas os Lembretes q o Insumo está no grupo
                    "SELECT
                                            1
                                        FROM
                                            insumos I
                                        WHERE
                                            I.id = item.insumo_id
                                        AND I.insumo_grupo_id = IG.id
                                    )
                                AND L.deleted_at IS NULL
                                AND L.lembrete_tipo_id = 2) ". //-- Subtrai a soma de todos prazos dos lembretes deste insumo
                    ",0)
                            ) 
                            DAY
                        ) ,
                        CURDATE()
                    ) sla
                FROM
                    ordem_de_compra_itens item
                JOIN ordem_de_compras OC ON OC.id = item.ordem_de_compra_id
                JOIN planejamento_compras PC ON PC.insumo_id = item.insumo_id
                AND PC.grupo_id = item.grupo_id
                AND PC.subgrupo1_id = item.subgrupo1_id
                AND PC.subgrupo2_id = item.subgrupo2_id
                AND PC.subgrupo3_id = item.subgrupo3_id
                AND PC.servico_id = item.servico_id
                JOIN planejamentos PL ON PL.id = PC.planejamento_id
                WHERE
                    item.id = ordem_de_compra_itens.id
                    AND PL.deleted_at IS NULL
                    AND PC.deleted_at IS NULL
                LIMIT 1) = ?", ["$keyword"]);
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = OrdemDeCompraItem::query()
            ->select([
            'ordem_de_compra_itens.*',
            'obras.nome as obra',
            'insumos.nome as insumo_nome',
            'insumos.unidade_sigla',
            // -- Busca qtd dias de SLA
            DB::raw("(
                SELECT
                    DATEDIFF(
                        ADDDATE(
                            ordem_de_compra_itens.updated_at , ". //-- Data de alteração da ordem de compras itens
                "INTERVAL(
                                IFNULL(
                                (SELECT
                                    SUM(L.dias_prazo_minimo) prazo
                                FROM
                                    lembretes L
                                JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                WHERE
                                    EXISTS( ". //-- Busca apenas os Lembretes q o Insumo está no grupo
                "SELECT
                                            1
                                        FROM
                                            insumos I
                                        WHERE
                                            I.id = item.insumo_id
                                        AND I.insumo_grupo_id = IG.id
                                    )
                                AND L.deleted_at IS NULL
                                AND L.lembrete_tipo_id = 2) ". //-- Subtrai a soma de todos prazos dos lembretes deste insumo
                ",0)
                            ) 
                            DAY
                        ) ,
                        CURDATE()
                    ) sla
                FROM
                    ordem_de_compra_itens item
                JOIN ordem_de_compras OC ON OC.id = item.ordem_de_compra_id
                JOIN planejamento_compras PC ON PC.insumo_id = item.insumo_id
                AND PC.grupo_id = item.grupo_id
                AND PC.subgrupo1_id = item.subgrupo1_id
                AND PC.subgrupo2_id = item.subgrupo2_id
                AND PC.subgrupo3_id = item.subgrupo3_id
                AND PC.servico_id = item.servico_id
                JOIN planejamentos PL ON PL.id = PC.planejamento_id
                WHERE
                    item.id = ordem_de_compra_itens.id
                    AND PL.deleted_at IS NULL
                    AND PC.deleted_at IS NULL
                LIMIT 1    
                ) as sla"),
        ])
            ->join('ordem_de_compras','ordem_de_compras.id','ordem_de_compra_itens.ordem_de_compra_id')
            ->join('obras','obras.id','ordem_de_compra_itens.obra_id')
            ->join('insumos','insumos.id','ordem_de_compra_itens.insumo_id')
            ->where('ordem_de_compras.aprovado','1')
            ->whereNotExists(function ($query){
                $query->select(DB::raw('1'))
                    ->from('oc_item_qc_item')
                    ->join('qc_itens','qc_itens.id','oc_item_qc_item.qc_item_id')
                    ->join('quadro_de_concorrencias','quadro_de_concorrencias.id','qc_itens.quadro_de_concorrencia_id')
                    ->where('ordem_de_compra_item_id',DB::raw('ordem_de_compra_itens.id') )
                    ->where('quadro_de_concorrencias.qc_status_id','!=','6');
            })
            ->with('insumo','grupo','subgrupo1','subgrupo2','subgrupo3','servico');

        if($this->request()->get('obras')){
            if(count($this->request()->get('obras')) && $this->request()->get('obras')[0] != ""){
                $query->whereIn('ordem_de_compra_itens.obra_id',$this->request()->get('obras'));
            }
        }
        if($this->request()->get('ocs')){
            if(count($this->request()->get('ocs')) && $this->request()->get('ocs')[0] != "") {
                $query->whereIn('ordem_de_compra_itens.ordem_de_compra_id', $this->request()->get('ocs'));
            }
        }
        if($this->request()->get('insumo_grupos')){
            if(count($this->request()->get('insumo_grupos')) && $this->request()->get('insumo_grupos')[0] != "") {
                $query->whereIn('insumos.insumo_grupo_id', $this->request()->get('insumo_grupos'));
            }
        }
        if($this->request()->get('insumos')){
            if(count($this->request()->get('insumos')) && $this->request()->get('insumos')[0] != "") {
                $query->whereIn('ordem_de_compra_itens.insumo_id', $this->request()->get('insumos'));
            }
        }
        if($this->request()->get('cidades')){
            if(count($this->request()->get('cidades')) && $this->request()->get('cidades')[0] != "") {
                $query->whereIn('obras.cidade_id', $this->request()->get('cidades'));
            }
        }
        if($this->request()->get('farol')){
            if(count($this->request()->get('farol')) && $this->request()->get('farol')[0] != "") {
                $query->whereIn(DB::raw("IF(
                (
                SELECT
                    DATEDIFF(
                        ADDDATE(
                            ordem_de_compra_itens.updated_at , " . //-- Data de início do Planejamento
                    "INTERVAL(
                                IFNULL(
                                (SELECT
                                    SUM(L.dias_prazo_minimo) prazo
                                FROM
                                    lembretes L
                                JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                WHERE
                                    EXISTS( " . //-- Busca apenas os Lembretes q o Insumo está no grupo
                    "SELECT
                                            1
                                        FROM
                                            insumos I
                                        WHERE
                                            I.id = item.insumo_id
                                        AND I.insumo_grupo_id = IG.id
                                    )
                                AND L.deleted_at IS NULL
                                AND L.lembrete_tipo_id = 2) " . //-- Subtrai a soma de todos prazos dos lembretes deste insumo
                    ",0)
                            ) 
                            DAY
                        ) ,
                        CURDATE()
                    ) sla
                FROM
                    ordem_de_compra_itens item
                JOIN ordem_de_compras OC ON OC.id = item.ordem_de_compra_id
                JOIN planejamento_compras PC ON PC.insumo_id = item.insumo_id
                AND PC.grupo_id = item.grupo_id
                AND PC.subgrupo1_id = item.subgrupo1_id
                AND PC.subgrupo2_id = item.subgrupo2_id
                AND PC.subgrupo3_id = item.subgrupo3_id
                AND PC.servico_id = item.servico_id
                JOIN planejamentos PL ON PL.id = PC.planejamento_id
                WHERE
                    item.id = ordem_de_compra_itens.id
                    AND PL.deleted_at IS NULL
                    AND PC.deleted_at IS NULL
                LIMIT 1    
                )<=0,'vermelho', IF( (
                SELECT
                    DATEDIFF(
                        ADDDATE(
                            ordem_de_compra_itens.updated_at , " . //-- Data de início do Planejamento
                    "INTERVAL(
                                IFNULL(
                                (SELECT
                                    SUM(L.dias_prazo_minimo) prazo
                                FROM
                                    lembretes L
                                JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                WHERE
                                    EXISTS( " . //-- Busca apenas os Lembretes q o Insumo está no grupo
                    "SELECT
                                            1
                                        FROM
                                            insumos I
                                        WHERE
                                            I.id = item.insumo_id
                                        AND I.insumo_grupo_id = IG.id
                                    )
                                AND L.deleted_at IS NULL
                                AND L.lembrete_tipo_id = 2) " . //-- Subtrai a soma de todos prazos dos lembretes deste insumo
                    ",0)
                            ) 
                            DAY
                        ) ,
                        CURDATE()
                    ) sla
                FROM
                    ordem_de_compra_itens item
                JOIN ordem_de_compras OC ON OC.id = item.ordem_de_compra_id
                JOIN planejamento_compras PC ON PC.insumo_id = item.insumo_id
                AND PC.grupo_id = item.grupo_id
                AND PC.subgrupo1_id = item.subgrupo1_id
                AND PC.subgrupo2_id = item.subgrupo2_id
                AND PC.subgrupo3_id = item.subgrupo3_id
                AND PC.servico_id = item.servico_id
                JOIN planejamentos PL ON PL.id = PC.planejamento_id
                WHERE
                    item.id = ordem_de_compra_itens.id
                    AND PL.deleted_at IS NULL
                    AND PC.deleted_at IS NULL
                LIMIT 1    
                ) >30,'verde','amarelo') )"), $this->request()->get('farol'));
            }
        }

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax('')
            ->parameters([
                'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if((col+1)<max){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'placeholder\',\'Filtrar...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }else{
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'type\',\'checkbox\');
                            $(input).attr(\'id\',\'checkUncheckAll\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                $(\'.item_checks\').prop("checked", $(this).prop("checked"));
                            });
                            $(column.footer()).addClass(\'text-center\');
                        }
                    });
                }' ,
//                "lengthChange"=> true,
                "pageLength"=> 25,
                'dom' => 'Bfrltip',
                'scrollX' => false,
                'language'=> [
                    "url"=> "/vendor/datatables/Portuguese-Brasil.json"
                ],
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                        'extend'  => 'collection',
                        'text'    => '<i class="fa fa-download"></i> Export',
                        'buttons' => [
                            'csv',
                            'excel',
                            'pdf',
                        ],
                    ],
                    'colvis'
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'obra' => ['name' => 'obras.nome', 'data' => 'obra'],
            'OC' => ['name' => 'ordem_de_compra_itens.ordem_de_compra_id', 'data' => 'ordem_de_compra_id'],
            'und' => ['name' => 'unidade_sigla', 'data' => 'unidade_sigla'],
//            'Codigo' => ['name' => 'ordem_de_compra_itens.codigo_insumo', 'data' => 'codigo_insumo'],
            'Insumo' => ['name' => 'insumos.nome', 'data' => 'insumo_nome'],
            'qtd' => ['name' => 'ordem_de_compra_itens.qtd', 'data' => 'qtd'],
//            'urgente' => ['name' => 'ordem_de_compra_itens.emergencial', 'data' => 'ordem_de_compra_itens.emergencial'],
            'sla' => ['name' => 'sla', 'data' => 'sla'],
            'action' => ['title' => 'Selecionar', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10px'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'insumos_aprovados_' . time();
    }
}
