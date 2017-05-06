<?php

namespace App\DataTables;

use App\Models\QcItem;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class QcItensDataTable extends DataTable
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
            ->editColumn('action', 'quadro_de_concorrencias.qc_itens_datatables_actions')
            ->editColumn('qtd', function($obj){
                return number_format($obj->qtd,2,',','.');
            })
            ->filterColumn('insumo_nome', function($query, $keyword){
                $query->where(function($subquery) use($keyword){
                    $subquery->where('insumos.codigo','LIKE','%'.$keyword.'%');
                    $subquery->orWhere('insumos.nome','LIKE','%'.$keyword.'%');
                });
            })
            ->filterColumn('qc_itens.qtd',function($query, $keyword){
                $query->where('qtd','LIKE','%'.str_replace(',','.',str_replace('.','',$keyword)).'%');
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
        $query = QcItem::query()
            ->select([
                'qc_itens.*',
                DB::raw('(SELECT COUNT(1) FROM oc_item_qc_item WHERE qc_item_id = qc_itens.id) as oci_qtd'),
                DB::raw("(
                            SELECT GROUP_CONCAT(obras.nome SEPARATOR ', ')
                            FROM oc_item_qc_item 
                            JOIN ordem_de_compra_itens OCI ON OCI.id = oc_item_qc_item.ordem_de_compra_item_id
                            JOIN obras on obras.id = OCI.obra_id
                            WHERE qc_item_id = qc_itens.id
                            GROUP BY oc_item_qc_item.qc_item_id
                         ) as obras"),
//                'obras.nome as obra',
                DB::raw("CONCAT(insumos.codigo,' - ', insumos.nome) as insumo_nome"),
            ])
        ->join('insumos','insumos.id','qc_itens.insumo_id');

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
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            })
                            .keydown(function(event){
                                if(event.keyCode == 13) {
                                    event.preventDefault();
                                    column.search($(this).val(), false, false, true).draw();
                                    return false;
                                }
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
                'dom' => 'Brltip',
                'scrollX' => false,
                'language'=> [
                    "url"=> "/vendor/datatables/Portuguese-Brasil.json"
                ],
                'buttons' => [
//                    'print',
//                    'reset',
//                    'reload',
//                    [
//                        'extend'  => 'collection',
//                        'text'    => '<i class="fa fa-download"></i> Export',
//                        'buttons' => [
//                            'csv',
//                            'excel',
//                            'pdf',
//                        ],
//                    ],
//                    'colvis'
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
            'id' => ['name' => 'qc_itens.id', 'data' => 'id', 'width'=>'8%'],
            'Insumo' => ['name' => 'insumo_nome', 'data' => 'insumo_nome'],
            'qtd' => ['name' => 'qc_itens.qtd', 'data' => 'qtd'],
            'Itens (oc)' => ['name' => 'oci_qtd', 'data' => 'oci_qtd', 'width'=>'7%'],
            'Obra(s)' => ['name' => 'obras', 'data' => 'obras', 'width'=>'12%'],
            'action' => ['title'          => 'Ações', 'printable'      => false, 'width'=>'30px'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'itens_quadro_de_concorrencia_' . time();
    }
}
