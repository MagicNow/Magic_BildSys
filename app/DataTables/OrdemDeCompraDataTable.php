<?php

namespace App\DataTables;

use App\Models\OrdemDeCompra;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class OrdemDeCompraDataTable extends DataTable
{

    private $query_status ='
                 IFNULL(
                    (
                        SELECT 
                            SUM(orcamentos.preco_total)
                        FROM
                            ordem_de_compra_itens
                                INNER JOIN
                            orcamentos ON orcamentos.insumo_id = ordem_de_compra_itens.insumo_id
                                AND orcamentos.grupo_id = ordem_de_compra_itens.grupo_id
                                AND orcamentos.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                                AND orcamentos.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                                AND orcamentos.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                                AND orcamentos.servico_id = ordem_de_compra_itens.servico_id
                                AND orcamentos.obra_id = ordem_de_compra_itens.obra_id
                                AND orcamentos.ativo = 1
                        WHERE
                            ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                                AND ordem_de_compra_itens.deleted_at IS NULL
                    ), 0
                 ) 
                 - 
                 IFNULL(
                    (
                        SELECT 
                            SUM(ordem_de_compra_itens.valor_total)
                        FROM
                            ordem_de_compra_itens
                                INNER JOIN
                            orcamentos ON orcamentos.insumo_id = ordem_de_compra_itens.insumo_id
                                AND orcamentos.grupo_id = ordem_de_compra_itens.grupo_id
                                AND orcamentos.subgrupo1_id = ordem_de_compra_itens.subgrupo1_id
                                AND orcamentos.subgrupo2_id = ordem_de_compra_itens.subgrupo2_id
                                AND orcamentos.subgrupo3_id = ordem_de_compra_itens.subgrupo3_id
                                AND orcamentos.servico_id = ordem_de_compra_itens.servico_id
                                AND orcamentos.obra_id = ordem_de_compra_itens.obra_id
                                AND orcamentos.ativo = 1
                        WHERE
                            ordem_de_compra_itens.ordem_de_compra_id = ordem_de_compras.id
                                AND ordem_de_compra_itens.deleted_at IS NULL
                    ), 0
                 )';
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'ordem_de_compras.datatables_actions')
            ->editColumn('status', function($obj){
                if($obj->status >= 0){
                    return "<h4><i class='fa fa-circle green'></i></h4>";
                }else{
                    return "<h4><i class='fa fa-circle red'></i></h4>";
                }
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $ordemDeCompras = OrdemDeCompra::query()
            ->select([
                'ordem_de_compras.id',
                'obras.nome as obra',
                'users.name as usuario',
                'oc_status.nome as situacao',
                'ordem_de_compras.obra_id',
                DB::raw($this->query_status. 'as status')
            ])
            ->join('obras', 'obras.id', '=', 'ordem_de_compras.obra_id')
            ->join('oc_status', 'oc_status.id', '=', 'ordem_de_compras.oc_status_id')
            ->join('users', 'users.id', '=', 'ordem_de_compras.user_id')
            ->whereRaw('EXISTS (SELECT 1 FROM obra_users WHERE obra_users.obra_id = obras.id AND user_id=?)', auth()->id())
            ->where('ordem_de_compras.oc_status_id', '!=', 6)
            ->orderBy('ordem_de_compras.id','DESC');

        if($this->request()->get('oc_status_id')){
            if(count($this->request()->get('oc_status_id')) && $this->request()->get('oc_status_id')[0] != "") {
                $ordemDeCompras->where('oc_status.id', $this->request()->get('oc_status_id'));
            }
        }

        if($this->request()->get('status_oc') == '0'){
            $ordemDeCompras->where(DB::raw($this->query_status), '>=', 0);
        }

        if($this->request()->get('status_oc') == '1'){
            $ordemDeCompras->where(DB::raw($this->query_status), '<', 0);
        }

//        echo $ordemDeCompras->toSql();
//        die();

        return $this->applyScopes($ordemDeCompras);
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
                'responsive'=> 'true',
                'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if((col+2)<max){
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
                'dom' => 'Bfrltip',
                'scrollX' => false,
                'language'=> [
                    "url"=> "/vendor/datatables/Portuguese-Brasil.json"
                ],
                'buttons' => [
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'núm Oc' => ['name' => 'id', 'data' => 'id'],
            'obra' => ['name' => 'obras.nome', 'data' => 'obra'],
            'usuário' => ['name' => 'users.name', 'data' => 'usuario'],
            'situação' => ['name' => 'oc_status.nome', 'data' => 'situacao'],
            'status' => ['name' => 'status', 'data' => 'status', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false],
            'action' => ['title' => 'visualizar OC', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'15%', 'class' => 'all']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ordemDeCompras';
    }
}
