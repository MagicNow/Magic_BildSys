<?php

namespace App\DataTables;

use App\Models\MemoriaCalculo;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class MemoriaCalculoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'memoria_calculos.datatables_actions')
            ->editColumn('modo', function($obj){
                if($obj->modo=='T'){
                    return 'TORRE';
                }
                if($obj->modo=='C'){
                    return 'CARTELA';
                }
                if($obj->modo=='U'){
                    return 'UNIDADE';
                }
            })
            ->filterColumn('modo', function($query,$search){
                $query->where('modo', substr($search,0,1));
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
        $memoriaCalculos = MemoriaCalculo::query()
            ->select([
                'memoria_calculos.id',
                'users.name as usuario',
                'memoria_calculos.nome',
                'memoria_calculos.padrao',
                'obras.nome as obra',
                'memoria_calculos.modo',
                DB::raw('(SELECT 1 FROM memoria_calculo_blocos 
                JOIN mc_medicao_previsoes ON mc_medicao_previsoes.memoria_calculo_bloco_id = memoria_calculo_blocos.id
                WHERE memoria_calculo_id = memoria_calculos.id LIMIT 1) as utilizado')
            ])
            ->join('obras','obras.id','obra_id')
            ->join('users','users.id','user_id');

        return $this->applyScopes($memoriaCalculos);
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
                        }
                    });
                }' ,
                'dom' => 'Bfrtip',
                'scrollX' => false,
                'language'=> [
                    "url"=> asset("vendor/datatables/Portuguese-Brasil.json")
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
    private function getColumns()
    {
        return [
            'obra' => ['name' => 'obras.nome', 'data' => 'obra'],
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'usuário' => ['name' => 'users.name', 'data' => 'usuario'],
            'modo' => ['name' => 'modo', 'data' => 'modo', 'width'=>'10%'],
            'action' => ['title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'memoriaCalculos';
    }
}
