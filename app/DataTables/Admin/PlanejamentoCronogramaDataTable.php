<?php

namespace App\DataTables\Admin;

use App\Models\Obra;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class PlanejamentoCronogramaDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.planejamento_cronogramas.datatables_actions')
            ->editColumn('data_upload',function ($obj){
                return $obj->data_upload ? with(new\Carbon\Carbon($obj->data_upload))->format('d/m/Y') : '';
            })
            ->filterColumn('data_upload', function ($query, $keyword) {
                $query->whereRaw("EXISTS(SELECT 1 FROM planejamentos WHERE DATE_FORMAT(planejamentos.data_upload,'%d/%m/%Y') like ? AND planejamentos.obra_id = obras.id)", ["%$keyword%"]);
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
        $planejamentoCronogramas = Obra::query()
            ->select('obras.nome','obras.id',
                DB::raw("(select planejamentos.data_upload
                    from planejamentos planejamentos
                    where obras.id = planejamentos.obra_id
                    order by data_upload DESC
                    limit 1) as data_upload"));

//        dd($planejamentoCronogramas->toSql());


//        select obras.nome as obra
//	  ,(select planejamentos.data_upload
//		from planejamentos planejamentos
//		where obras.id = planejamentos.obra_id
//		order by data_upload DESC
//		limit 1) as data_upload
//FROM obras

        return $this->applyScopes($planejamentoCronogramas);
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
//            ->addAction(['width' => '10%'])
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
            'obra' => ['name' => 'obras.nome', 'data' => 'nome'],
            'data Upload' => ['name' => 'data_upload', 'data' => 'data_upload'],
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
        return 'planejamentoCronogramas';
    }
}
