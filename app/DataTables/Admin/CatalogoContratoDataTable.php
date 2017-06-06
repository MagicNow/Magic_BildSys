<?php

namespace App\DataTables\Admin;

use App\Models\CatalogoContrato;
use Form;
use Yajra\Datatables\Services\DataTable;

class CatalogoContratoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('arquivo',function ($obj){
                if($obj->arquivo){
                    return '<a href="'.$obj->arquivo.'" download>Baixar arquivo</a>';
                }else{
                    return '';
                }
            })
            ->editColumn('data',function ($obj){
                return $obj->data ? with(new\Carbon\Carbon($obj->data))->format('d/m/Y') : '';
            })
            ->filterColumn('catalogo_contratos.data', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(catalogo_contratos.data,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('catalogo_contratos.valor', function ($query, $keyword) {
                $pontos = array(",");
                $value = str_replace('.','',$keyword);
                $result = str_replace( $pontos, ".", $value);

                $query->whereRaw("(catalogo_contratos.valor) like ?", ["%$result%"]);
            })
            ->editColumn('action', 'catalogo_contratos.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $catalogoContratos = CatalogoContrato::query()->select([
            'catalogo_contratos.id',
            'fornecedores.nome as fornecedor',
            'catalogo_contratos.data',
            'catalogo_contratos.valor',
            'catalogo_contratos.arquivo',
        ])
        ->join('fornecedores','catalogo_contratos.fornecedor_id','fornecedores.id');

        return $this->applyScopes($catalogoContratos);
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
                'responsive' => 'true',

                 'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if((col+1)<(max-1)){
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
            'fornecedor' => ['name' => 'fornecedores.nome', 'data' => 'fornecedor'],
            'data' => ['name' => 'catalogo_contratos.data', 'data' => 'data'],
            'valor' => ['name' => 'catalogo_contratos.valor', 'data' => 'valor'],
            'arquivo' => ['name' => 'catalogo_contratos.arquivo', 'data' => 'arquivo', 'printable' => false, 'exportable' => false],
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
        return 'catalogoContratos';
    }
}
