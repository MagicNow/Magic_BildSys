<?php

namespace App\DataTables;

use DB;
use Form;
use Auth;
use App\Models\QuadroDeConcorrencia;
use Yajra\Datatables\Services\DataTable;

class QuadroDeConcorrenciaDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'quadro_de_concorrencias.datatables_actions')
            ->editColumn('created_at', function($obj){
                return $obj->created_at ? with(new\Carbon\Carbon($obj->created_at))->format('d/m/Y H:i') : '';
            })
            ->editColumn('situacao', function($obj){
                return '<i class="fa fa-circle" aria-hidden="true" style="color:'.$obj->situacao_cor.'"></i> '.$obj->situacao;
            })
            ->filterColumn('quadro_de_concorrencias.created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(quadro_de_concorrencias.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('quadro_de_concorrencias.updated_at', function($obj){
                return $obj->updated_at ? with(new\Carbon\Carbon($obj->updated_at))->format('d/m/Y H:i') : '';
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(quadro_de_concorrencias.updated_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('fornecedores', function($query, $keyword){
                $query->whereRaw('(SELECT 
                            count(1) 
                          FROM qc_fornecedor 
                          WHERE 
                            quadro_de_concorrencia_id = quadro_de_concorrencias.id
                            AND rodada = quadro_de_concorrencias.rodada_atual
                          ) = ?',[$keyword]);
            })
            ->filterColumn('propostas', function($query, $keyword){
                $query->whereRaw('(SELECT 
                            count(1) 
                            FROM qc_fornecedor 
                            WHERE 
                                quadro_de_concorrencia_id = quadro_de_concorrencias.id
                                AND rodada = quadro_de_concorrencias.rodada_atual
                                AND (
                                    qc_fornecedor.desistencia_motivo_id IS NOT NULL
                                    OR
                                    EXISTS (
                                        SELECT 1 FROM qc_item_qc_fornecedor
                                        WHERE qc_fornecedor_id = qc_fornecedor.id
                                        )
                                    )
                         ) = ?',[$keyword]);
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
        $user = Auth::user();

        $quadroDeConcorrencias = QuadroDeConcorrencia::query()
            ->select([
                'quadro_de_concorrencias.id',
                'quadro_de_concorrencias.rodada_atual',
                'quadro_de_concorrencias.created_at',
                'quadro_de_concorrencias.updated_at',
                'users.name as usuario',
                'qc_status.nome as situacao',
                'qc_status.cor as situacao_cor',
                'quadro_de_concorrencias.qc_status_id',
                DB::raw('(SELECT 
                            count(1) 
                          FROM qc_fornecedor 
                          WHERE 
                            quadro_de_concorrencia_id = quadro_de_concorrencias.id
                            AND rodada = quadro_de_concorrencias.rodada_atual
                          ) as fornecedores'),
                DB::raw('(SELECT 
                            count(1) 
                            FROM qc_fornecedor 
                            WHERE 
                                quadro_de_concorrencia_id = quadro_de_concorrencias.id
                                AND rodada = quadro_de_concorrencias.rodada_atual
                                AND (
                                    qc_fornecedor.desistencia_motivo_id IS NOT NULL
                                    OR
                                    EXISTS (
                                        SELECT 1 FROM qc_item_qc_fornecedor
                                        WHERE qc_fornecedor_id = qc_fornecedor.id
                                        )
                                    )
                         ) as propostas'),
            ])
            ->join('users','users.id','quadro_de_concorrencias.user_id')
            ->join('qc_status','qc_status.id','quadro_de_concorrencias.qc_status_id');

        if($user->fornecedor) {
            $quadroDeConcorrencias
                ->join('qc_fornecedor', 'qc_fornecedor.quadro_de_concorrencia_id', 'quadro_de_concorrencias.id')
                ->leftJoin('qc_item_qc_fornecedor', 'qc_item_qc_fornecedor.qc_fornecedor_id', 'qc_fornecedor.id')
                ->where('quadro_de_concorrencias.qc_status_id', 7)
                ->where('qc_fornecedor.fornecedor_id', $user->fornecedor->id)
                ->whereNull('qc_item_qc_fornecedor.id')
                ->whereNull('qc_fornecedor.desistencia_motivo_id')
                ->whereNull('qc_fornecedor.desistencia_texto')
                ->whereRaw('qc_fornecedor.rodada = quadro_de_concorrencias.rodada_atual');
        }

        return $this->applyScopes($quadroDeConcorrencias);
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
        $columns = [
            'id' => ['name' => 'quadro_de_concorrencias.id', 'data' => 'id', 'width'=>'25px'],
            'situação' => ['name' => 'qc_status.nome', 'data' => 'situacao'],
            'rodada_atual' => ['name' => 'rodada_atual', 'data' => 'rodada_atual'],
            'atualizadoEm' => ['name' => 'quadro_de_concorrencias.updated_at', 'data' => 'updated_at', 'width'=>'12%'],
            'rodada' => ['name' => 'rodada_atual', 'data' => 'rodada_atual', 'width'=>'6%'],
            'fornecedores' => ['name' => 'fornecedores', 'data' => 'fornecedores', 'width'=>'6%'],
            'propostas' => ['name' => 'propostas', 'data' => 'propostas', 'width'=>'6%'],
            'action' => ['searchable'=>false, 'orderable'=>false,'title' => '#', 'printable' => false, 'width'=>'10%'],
        ];

        if(!auth()->user()->fornecedor) {
            $columns['usuario'] = ['name' => 'users.name', 'data' => 'usuario'];
            $columns['criadoEm'] = ['name' => 'quadro_de_concorrencias.created_at', 'data' => 'created_at', 'width'=>'12%'];
        }

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'quadroDeConcorrencias';
    }
}
