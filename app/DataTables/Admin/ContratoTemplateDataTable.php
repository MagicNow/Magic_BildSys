<?php

namespace App\DataTables\Admin;

use App\Models\ContratoTemplate;
use Form;
use Yajra\Datatables\Services\DataTable;

class ContratoTemplateDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'admin.contrato_templates.datatables_actions')
            ->editColumn('updated_at', function($obj){
                return $obj->updated_at ? with(new\Carbon\Carbon($obj->updated_at))->format('d/m/Y H:i') : '';
            })
            ->editColumn('usuario', function($obj){
                return $obj->usuario ? $obj->usuario : 'Automático';
            })
            ->editColumn('tipo', function($obj){
                return $obj->tipo=='A' ? 'Acordo' : ($obj->tipo=='M'?'Materiais':'Serviço/Material');
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(obras.updated_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
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
        $contratoTemplates = ContratoTemplate::query()
        ->leftJoin('users','users.id','user_id')
        ->select([
            'contrato_templates.id',
            'contrato_templates.nome',
            'contrato_templates.updated_at',
            'contrato_templates.tipo',
            'users.name as usuario',
        ]);

        return $this->applyScopes($contratoTemplates);
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
                'responsive' => 'true',
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
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'cadastrado/alteradoPor' => ['name' => 'users.name', 'data' => 'usuario'],
            'cadastrado/AlteradoEm' => ['name' => 'updated_at', 'data' => 'updated_at'],
            'tipo' => ['name' => 'tipo', 'data' => 'tipo','width'=>'10%'],
            'action' => ['title' => '#', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'contratoTemplates';
    }
}
