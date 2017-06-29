<?php

namespace App\DataTables;

use App\Models\OrdemDeCompra;
use App\Repositories\OrdemDeCompraRepository;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class OrdemDeCompraDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'ordem_de_compras.datatables_actions')
            ->editColumn('status', function($obj){
                $saldoDisponivel = OrdemDeCompraRepository::saldoDisponivel($obj->id, $obj->obra_id);
                $obj->status = $saldoDisponivel;

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
                DB::raw('0 as status'),
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

        $array_oc_id = [];

        if($this->request()->get('status_oc') == '0'){
            foreach ($ordemDeCompras->get() as $ordemDeCompra) {
                $saldoDisponivel = OrdemDeCompraRepository::saldoDisponivel($ordemDeCompra->id, $ordemDeCompra->obra_id);
                if($saldoDisponivel >= 0) {
                    array_push($array_oc_id, $ordemDeCompra->id);
                }
            }
            $ordemDeCompras->whereIn('ordem_de_compras.id', $array_oc_id);
        }

        if($this->request()->get('status_oc') == '1'){
            foreach ($ordemDeCompras->get() as $ordemDeCompra) {
                $saldoDisponivel = OrdemDeCompraRepository::saldoDisponivel($ordemDeCompra->id, $ordemDeCompra->obra_id);
                if($saldoDisponivel < 0) {
                    array_push($array_oc_id, $ordemDeCompra->id);
                }
            }
            $ordemDeCompras->whereIn('ordem_de_compras.id', $array_oc_id);
        }

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
