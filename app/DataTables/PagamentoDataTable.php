<?php

namespace App\DataTables;

use App\Models\Pagamento;
use Form;
use Yajra\Datatables\Services\DataTable;

class PagamentoDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'pagamentos.datatables_actions')
            ->editColumn('data_emissao', function($obj){
                return $obj->data_emissao->format('d/m/Y');
            })
            ->editColumn('enviado_integracao', function($obj){
                return '<i class="fa fa-'.($obj->enviado_integracao?'check text-success':'times text-danger').'"></i>';
            })
            ->editColumn('integrado', function($obj){
                return '<i class="fa fa-'.($obj->integrado?'check text-success':'times text-danger').'"></i>';
            })
            ->editColumn('valor', function($obj){
                return '<div class="text-right">'.float_to_money($obj->valor).'</div>';
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
        $pagamentos = Pagamento::query()
            ->select([
                'pagamentos.id',
                'pagamentos.contrato_id',
                'pagamentos.numero_documento',
                'pagamentos.data_emissao',
                'pagamentos.valor',
                'pagamentos.notas_fiscal_id',
                'pagamentos.enviado_integracao',
                'pagamentos.integrado',
                'obras.nome as obra',
                'fornecedores.nome as fornecedor',
                'pagamento_condicoes.codigo',
                'documento_financeiro_tipos.codigo_mega',
            ])
            ->join('obras','obras.id','pagamentos.obra_id')
            ->join('fornecedores','fornecedores.id','pagamentos.fornecedor_id')
            ->join('pagamento_condicoes','pagamento_condicoes.id','pagamentos.pagamento_condicao_id')
            ->join('documento_financeiro_tipos','documento_financeiro_tipos.id','pagamentos.documento_financeiro_tipo_id')
        ;

        return $this->applyScopes($pagamentos);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        $params = [
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
            "pageLength"=> 100,
            'scrollX' => false,
            'language'=> [
                "url"=> "/vendor/datatables/Portuguese-Brasil.json"
            ],
            'buttons' => ['print','colvis']
        ];
        if(request()->segment(count(request()->segments()))=='pagamentos'){
            $params = [
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
            ];
        }
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax('')
            ->parameters($params);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        if(request()->segment(count(request()->segments()))=='pagamentos'){
            return [
                'contrato' => ['name' => 'contrato_id', 'data' => 'contrato_id'],
                'obra' => ['name' => 'obras.nome', 'data' => 'obra'],
                'número_documento' => ['name' => 'numero_documento', 'data' => 'numero_documento'],
                'fornecedor' => ['name' => 'fornecedores.nome', 'data' => 'fornecedor'],
                'data_emissão' => ['name' => 'data_emissao', 'data' => 'data_emissao'],
                'valor' => ['name' => 'valor', 'data' => 'valor'],
                'condiçõesDePagamento' => ['name' => 'pagamento_condicoes.codigo', 'data' => 'codigo'],
                'TipoDeDocumento' => ['name' => 'documento_financeiro_tipos.codigo_mega', 'data' => 'codigo_mega'],
//                'notas_fiscal' => ['name' => 'notas_fiscal_id', 'data' => 'notas_fiscal_id'],
                'enviado_mega' => ['name' => 'enviado_integracao', 'data' => 'enviado_integracao'],
                'integrado_mega' => ['name' => 'integrado', 'data' => 'integrado'],
                'action' => ['title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
            ];
        }
        return [
            'id'=>['name'=>'id','data'=>'id','width'=>'5%'],
            'número_documento' => ['name' => 'numero_documento', 'data' => 'numero_documento'],
            'fornecedor' => ['name' => 'fornecedores.nome', 'data' => 'fornecedor'],
            'data_emissão' => ['name' => 'data_emissao', 'data' => 'data_emissao'],
            'valor' => ['name' => 'valor', 'data' => 'valor'],
            'condiçõesDePagamento' => ['name' => 'pagamento_condicoes.codigo', 'data' => 'codigo'],
            'TipoDeDocumento' => ['name' => 'documento_tipos.sigla', 'data' => 'sigla'],
            'notas_fiscal' => ['name' => 'notas_fiscal_id', 'data' => 'notas_fiscal_id'],
            'enviado_mega' => ['name' => 'enviado_integracao', 'data' => 'enviado_integracao'],
            'integrado_mega' => ['name' => 'integrado', 'data' => 'integrado'],
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
        return 'pagamentos';
    }
}
