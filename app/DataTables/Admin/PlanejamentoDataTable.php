<?php

namespace App\DataTables\Admin;

use App\Models\Planejamento;
use Form;
use Yajra\Datatables\Services\DataTable;

class PlanejamentoDataTable extends DataTable
{
    protected $obra = null;
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'admin.planejamentos.datatables_actions')
            ->editColumn('obra_id',function ($obj){
                return $obj->obra_id ? $obj->obra->nome : '';
            })
            ->editColumn('data',function ($obj){
                return $obj->data ? with(new\Carbon\Carbon($obj->data))->format('d/m/Y') : '';
            })
            ->filterColumn('data', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(planejamentos.data,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('data_fim',function ($obj){
                return $obj->data_fim ? with(new\Carbon\Carbon($obj->data_fim))->format('d/m/Y') : '';
            })
            ->filterColumn('data_fim', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(planejamentos.data_fim,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('prazo',function ($obj){
                return $obj->prazo ? $obj->prazo . ' dias ' : '';
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
        $planejamentos = Planejamento::query()
            ->select([
                'planejamentos.id',
                'obras.nome as obra',
                'planejamentos.tarefa',
                'planejamentos.data',
                'planejamentos.prazo',
                'planejamentos.data_fim',
                'planejamentos.resumo',
                'planejamentos.created_at'
            ])
        ->join('obras','obras.id','planejamentos.obra_id');
        if($this->obra){
            $planejamentos->where('planejamentos.obra_id', $this->obra);
        }

        return $this->applyScopes($planejamentos);
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

    public function porObra($id){
        $this->obra = $id;
        return $this;
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
            'tarefa' => ['name' => 'tarefa', 'data' => 'tarefa'],
            'data_início' => ['name' => 'data', 'data' => 'data'],
            'prazo' => ['name' => 'prazo', 'data' => 'prazo'],
            'data_fim' => ['name' => 'data_fim', 'data' => 'data_fim'],
            'resumo' => ['name' => 'resumo', 'data' => 'resumo'],
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
        return 'planejamentos';
    }
}
