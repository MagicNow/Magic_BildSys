<?php

namespace App\DataTables\Admin;

use App\Models\TemplatePlanilha;
use Form;
use Yajra\Datatables\Services\DataTable;

class TemplatePlanilhaDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'admin.template_planilhas.datatables_actions')
            ->editColumn('colunas',function ($obj){
                if($obj->colunas) {
                    $column = [];
                    foreach (json_decode($obj->colunas) as $key => $coluna) {
                        $column[] = 'coluna '. $key. ': ' .str_replace(',','',$coluna);
                    }
                    return implode('<br/>',$column);
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
        $templatePlanilhas = TemplatePlanilha::query();

        return $this->applyScopes($templatePlanilhas);
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
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'modulo' => ['name' => 'modulo', 'data' => 'modulo'],
            'colunas' => ['name' => 'colunas', 'data' => 'colunas'],
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
        return 'templatePlanilhas';
    }
}
