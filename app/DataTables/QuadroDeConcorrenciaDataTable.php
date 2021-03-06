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
            ->editColumn('updated_at', function($obj){
                return $obj->updated_at ? with(new\Carbon\Carbon($obj->updated_at))->format('d/m/Y H:i') : '';
            })
            ->editColumn('usuario', function($obj){
                return $obj->usuario ?
                    '<span class="label label-info">'.$obj->usuario.'</span>' :
                    '<span class="label label-default"> <i class="fa fa-magic" aria-hidden="true"></i> Catálogo</span>';
            })
            ->editColumn('situacao', function($obj){
                return '<i class="fa fa-circle" aria-hidden="true" style="color:'.$obj->situacao_cor.'"></i> '.$obj->situacao;
            })
            ->editColumn('obras', function($obj){
                return "<textarea class='form-control' disabled 
                                        style='cursor: auto;background-color: transparent;resize: vertical;'>".
                            $obj->obras
                ."</textarea>";
            })
            ->filterColumn('quadro_de_concorrencias.created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(quadro_de_concorrencias.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
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
            ->filterColumn('users.name', function($query, $keyword){
                $query->whereRaw("IFNULL(users.name,'catalogo') LIKE ?",['%'.$keyword.'%']);
            })

            ->filterColumn('obras', function($query, $keyword){
                $query->whereRaw('(
                    SELECT 
                        GROUP_CONCAT(nome SEPARATOR ", ")
                    FROM
                        obras
                    WHERE
                        id IN (SELECT 
                                obra_id
                            FROM
                                ordem_de_compra_itens
                            WHERE
                                id IN (SELECT 
                                        ordem_de_compra_item_id
                                    FROM
                                        oc_item_qc_item
                                    WHERE
                                        qc_item_id IN (SELECT 
                                                id
                                            FROM
                                                qc_itens
                                            WHERE
                                                qc_itens.quadro_de_concorrencia_id = quadro_de_concorrencias.id)))
                ) LIKE ?', ['%'.$keyword.'%']);
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
                DB::raw('
                  exists (
                    select
                        1
                      from
                         `qc_item_qc_fornecedor`
                      join
                        `qc_fornecedor`
                        on
                           `qc_fornecedor`.`id` = `qc_item_qc_fornecedor`.`qc_fornecedor_id`
                        where
                           `quadro_de_concorrencias`.`id` = `qc_fornecedor`.`quadro_de_concorrencia_id`
                        and
                           `qc_fornecedor`.`rodada` = `quadro_de_concorrencias`.`rodada_atual`
                   ) as tem_ofertas
               '),
                DB::raw('(
                    SELECT 
                        GROUP_CONCAT(nome SEPARATOR ", ")
                    FROM
                        obras
                    WHERE
                        id IN (SELECT 
                                obra_id
                            FROM
                                ordem_de_compra_itens
                            WHERE
                                id IN (SELECT 
                                        ordem_de_compra_item_id
                                    FROM
                                        oc_item_qc_item
                                    WHERE
                                        qc_item_id IN (SELECT 
                                                id
                                            FROM
                                                qc_itens
                                            WHERE
                                                qc_itens.quadro_de_concorrencia_id = quadro_de_concorrencias.id)))
                ) as obras')
           ])
            ->leftJoin('users','users.id','quadro_de_concorrencias.user_id')
            ->join('qc_status','qc_status.id','quadro_de_concorrencias.qc_status_id')
        ->with('contratos');

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
                'order'=> [ 0, "desc" ],
                'responsive'=> 'true',
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
            'Q&period;C&period;' => ['name' => 'quadro_de_concorrencias.id', 'data' => 'id', 'width'=>'10%'],
            'Obra(s)' => ['name' => 'obras', 'data' => 'obras', 'width'=>'20%'],
            'Status' => ['name' => 'qc_status.nome', 'data' => 'situacao', 'width'=>'20%'],
            'atualizado' => ['name' => 'quadro_de_concorrencias.updated_at', 'data' => 'updated_at', 'width'=>'12%'],
            'rodada' => ['name' => 'rodada_atual', 'data' => 'rodada_atual', 'width'=>'5%'],
            'fornecedores' => ['name' => 'fornecedores', 'data' => 'fornecedores', 'width'=>'5%'],
            'propostas' => ['name' => 'propostas', 'data' => 'propostas', 'width'=>'5%'],
        ];

        if(!auth()->user()->fornecedor) {
            $columns['usuário'] = ['name' => 'users.name', 'data' => 'usuario'];
            $columns['criado'] = ['name' => 'quadro_de_concorrencias.created_at', 'data' => 'created_at', 'width'=>'12%'];
        }

        $columns['action'] = [
            'title'      => 'Ações',
            'printable'  => false,
            'exportable' => false,
            'searchable' => false,
            'orderable'  => false,
            'class'      => 'all',
            'width'      => '15%'
        ];

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
