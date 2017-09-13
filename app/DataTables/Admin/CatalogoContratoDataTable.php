<?php

namespace App\DataTables\Admin;

use App\Models\CatalogoContrato;
use Form;
use Illuminate\Support\Facades\DB;
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
            ->editColumn('action', 'catalogo_contratos.datatables_actions')
            ->editColumn('insumos_catalogo',function($obj){
                return '<span class="label label-default" title="'.$obj->insumos_catalogo.'" data-toggle="tooltip" data-html="true" >
                            <i class="fa fa-info"></i>
                            Ver insumos
                            </span>';
            })
            ->filterColumn('insumos_catalogo', function ($query, $keyword) {
                $query->whereRaw("EXISTS (
                                        SELECT 1 
                                        FROM catalogo_contrato_insumos 
                                        JOIN insumos ON insumos.id = catalogo_contrato_insumos.insumo_id
                                         WHERE insumos.nome LIKE ? 
                                         AND catalogo_contrato_id = catalogo_contratos.id
                                        )", ["%$keyword%"]);
            })
            ->filterColumn('regionais', function ($query, $keyword) {
                $query->whereRaw("EXISTS (
                                        SELECT 1 
                                        FROM
                                        catalogo_contrato_regional
                                        JOIN regionais ON regionais.id = catalogo_contrato_regional.regional_id
                                         WHERE regionais.nome LIKE ? 
                                         AND catalogo_contrato_id = catalogo_contratos.id
                                        )", ["%$keyword%"]);
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
        $catalogoContratos = CatalogoContrato::select([
            'catalogo_contratos.id',
            'fornecedores.nome as fornecedor',
            'catalogo_contrato_status.nome as status',
            DB::raw("(
                SELECT 
                    GROUP_CONCAT(DISTINCT insumos.nome ORDER BY insumos.nome ASC SEPARATOR '<br>')
                FROM
                    catalogo_contrato_insumos
                    JOIN insumos ON insumos.id = catalogo_contrato_insumos.insumo_id
                WHERE catalogo_contrato_id = catalogo_contratos.id
            ) AS insumos_catalogo"),
            DB::raw("(
                SELECT 
                    GROUP_CONCAT(DISTINCT regionais.nome ORDER BY regionais.nome ASC SEPARATOR '<br>')
                FROM
                    catalogo_contrato_regional
                    JOIN regionais ON regionais.id = catalogo_contrato_regional.regional_id
                WHERE catalogo_contrato_id = catalogo_contratos.id
            ) AS regionais")
        ])
        ->join('fornecedores','catalogo_contratos.fornecedor_id','fornecedores.id')
        ->join('catalogo_contrato_status','catalogo_contratos.catalogo_contrato_status_id','catalogo_contrato_status.id');

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
            'insumos' => ['name' => 'insumos_catalogo', 'data' => 'insumos_catalogo'],
            'regionais' => ['name' => 'regionais', 'data' => 'regionais'],
            'situação' => ['name' => 'catalogo_contrato_status.nome', 'data' => 'status'],
            'action' => ['title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'11%']
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
