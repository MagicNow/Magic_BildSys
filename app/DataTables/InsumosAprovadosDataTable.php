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
        return $this->datatables
            ->eloquent($this->query())
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
            ->filterColumn('sla', function($query){
                
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
            // -- Busca qtd dias de SLA
            DB::raw("(
                SELECT
                    DATEDIFF(
                        SUBDATE(
                            PL.`data` , ". //-- Data de início do Planejamento
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
                                AND L.deleted_at IS NULL) ". //-- Subtrai a soma de todos prazos dos lembretes deste insumo
                ",0)
                                + ". // -- Subtrai tb os dias de workflow
                " IFNULL(
                                    (SELECT SUM(dias_prazo) prazo
                                        FROM workflow_alcadas
                                        WHERE EXISTS(SELECT 1 FROM workflow_usuarios WHERE workflow_alcada_id = workflow_alcadas.id ))
                                ,0)
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
                    ->where('ordem_de_compra_item_id',DB::raw('ordem_de_compra_itens.id') );
            })
            ->with('insumo','grupo','subgrupo1','subgrupo2','subgrupo3','servico');

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
//            ->addAction(['width' => '10%'])
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
                        }
                    });
                }' ,
                "lengthChange"=> true,
                "pageLength"=> 20,
                'dom' => 'Bfrtip',
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
            'O.C.' => ['name' => 'ordem_de_compra_itens.ordem_de_compra_id', 'data' => 'ordem_de_compra_id'],
            'Codigo' => ['name' => 'ordem_de_compra_itens.codigo_insumo', 'data' => 'codigo_insumo'],
            'Insumo' => ['name' => 'insumos.nome', 'data' => 'insumo_nome'],
            'qtd' => ['name' => 'ordem_de_compra_itens.qtd', 'data' => 'qtd'],
            'sla' => ['name' => 'sla', 'data' => 'sla'],
            'action' => ['title'          => '#', 'printable'      => false, 'width'=>'10px'],
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
