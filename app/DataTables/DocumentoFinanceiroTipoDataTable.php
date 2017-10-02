<?php

namespace App\DataTables;

use App\Models\DocumentoFinanceiroTipo;
use Form;
use Yajra\Datatables\Services\DataTable;

class DocumentoFinanceiroTipoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'documento_financeiro_tipos.datatables_actions')
            ->editColumn('retem_irrf', '{!! $retem_irrf?\'<i class="fa fa-check text-success"></i>\':\'<i class="fa fa-times text-danger"></i>\' !!}')
            ->editColumn('retem_impostos', '{!! $retem_impostos?\'<i class="fa fa-check text-success"></i>\':\'<i class="fa fa-times text-danger"></i>\' !!}')
            ->filterColumn('retem_irrf', function($query, $key){
                if(strtolower(substr($key,0,1))=='s'){
                    $query->where('retem_irrf',1);
                }else{
                    $query->where('retem_irrf',0);
                }
            })
            ->filterColumn('retem_impostos', function($query, $key){
                if(strtolower(substr($key,0,1))=='s'){
                    $query->where('retem_impostos',1);
                }else{
                    $query->where('retem_impostos',0);
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
        $documentoFinanceiroTipos = DocumentoFinanceiroTipo::query();

        return $this->applyScopes($documentoFinanceiroTipos);
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
    private function getColumns()
    {
        return [
            'codigo' => ['name' => 'codigo_mega', 'data' => 'codigo_mega', 'width'=>'10%'],
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'retem_irrf' => ['name' => 'retem_irrf', 'data' => 'retem_irrf', 'width'=>'10%'],
            'retem_impostos' => ['name' => 'retem_impostos', 'data' => 'retem_impostos', 'width'=>'10%'],
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
        return 'documentoFinanceiroTipos';
    }
}
