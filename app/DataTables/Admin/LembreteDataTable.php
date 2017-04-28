<?php

namespace App\DataTables\Admin;

use App\Models\Lembrete;
use Form;
use Yajra\Datatables\Services\DataTable;

class LembreteDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'admin.lembretes.datatables_actions')
            ->editColumn('dias_prazo_minimo', function($obj){
                return isset($obj->dias_prazo_minimo) ? $obj->dias_prazo_minimo . ' dias ' : '';
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
        $lembretes = Lembrete::query()->select([
            'lembretes.id',
            'lembretes.nome',
            'lembretes.dias_prazo_minimo',
            'lembrete_tipos.nome as tipo',
            'insumo_grupos.nome as grupo',
            'users.name as user',
        ])
            ->join('lembrete_tipos','lembrete_tipos.id','lembretes.lembrete_tipo_id')
            ->join('insumo_grupos','insumo_grupos.id','lembretes.insumo_grupo_id')
            ->join('users','users.id','lembretes.user_id')
        ;

        return $this->applyScopes($lembretes);
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
            // ->addAction(['width' => '10%'])
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
    private function getColumns()
    {
        return [
            'tipo' => ['name' => 'lembrete_tipos.nome', 'data' => 'tipo'],
            'nome' => ['name' => 'lembretes.nome', 'data' => 'nome'],
            'prazo' => ['name' => 'dias_prazo_minimo', 'data' => 'dias_prazo_minimo'],
            'grupo' => ['name' => 'dias_prazo_maximo', 'data' => 'grupo'],
            'cadastrado_por' => ['name' => 'users.name', 'data' => 'user'],
            'action' => ['title'          => '#', 'printable'      => false, 'width'=>'10%'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'lembretes';
    }
}
