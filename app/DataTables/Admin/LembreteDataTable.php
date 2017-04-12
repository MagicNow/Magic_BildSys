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
            ->addColumn('action', 'admin.lembretes.datatables_actions')
            ->editColumn('lembrete_tipo_id', function($obj){
                return $obj->lembreteTipo->nome;
            })
            ->editColumn('user_id', function($obj){
                return $obj->user->name;
            })
            ->editColumn('dias_prazo_minimo', function($obj){
                return isset($obj->dias_prazo_minimo) ? $obj->dias_prazo_minimo . ' dias ' : '';
            })
            ->editColumn('dias_prazo_maximo', function($obj){
                return isset($obj->dias_prazo_maximo) ? $obj->dias_prazo_maximo . ' dias ' : '';
            })
            ->editColumn('user_id', function($obj){
                return $obj->user->name;
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
        $lembretes = Lembrete::query();

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
            ->addAction(['width' => '10%'])
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
            'lembrete' => ['name' => 'lembrete_tipo_id', 'data' => 'lembrete_tipo_id'],
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'prazo_minimo' => ['name' => 'dias_prazo_minimo', 'data' => 'dias_prazo_minimo'],
            'prazo_maximo' => ['name' => 'dias_prazo_maximo', 'data' => 'dias_prazo_maximo'],
            'cadastrado_por' => ['name' => 'user_id', 'data' => 'user_id']
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
