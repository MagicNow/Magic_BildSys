<?php

namespace App\DataTables;

use App\Models\MedicaoServico;
use App\Models\WorkflowTipo;
use App\Models\WorkflowUsuario;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class MedicaoServicoDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('created_at', function($obj){
                return $obj->created_at ? with(new\Carbon\Carbon($obj->created_at))->format('d/m/Y H:i') : '';
            })
            ->editColumn('periodo_inicio', function($obj){
                return $obj->periodo_inicio ? with(new\Carbon\Carbon($obj->periodo_inicio))->format('d/m/Y') : '';
            })
            ->editColumn('periodo_termino', function($obj){
                return $obj->periodo_termino ? with(new\Carbon\Carbon($obj->periodo_termino))->format('d/m/Y') : '';
            })
            ->editColumn('descontos', function ($obj){
                return $obj->descontos ? float_to_money($obj->descontos) : '';
            })
            ->editColumn('finalizado', function ($obj){
                return $obj->finalizado ? ( is_null($obj->aprovado) ? 'Aguardando Aprovação' : ($obj->aprovado==1?'Aprovado': 'Reprovado') )  : 'Em Aberto';
            })
            ->editColumn('soma', function ($obj){
                return '<div class="text-right">'.($obj->soma ? float_to_money($obj->soma,'')  : '0').'</div>';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(medicao_servicos.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })

            ->filterColumn('periodo_inicio', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(medicao_servicos.periodo_inicio,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('periodo_termino', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(medicao_servicos.periodo_termino,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('insumo', function ($query, $keyword) {
                $query->whereRaw("CONCAT(contrato_item_apropriacoes.codigo_insumo, ' - ',insumos.nome) like ?", ["%$keyword%"]);
            })
            ->filterColumn('apropriacao', function ($query, $keyword) {
                $query->whereRaw("CONCAT(servicos.codigo,' - ', servicos.nome) like ?", ["%$keyword%"]);
            })
            ->filterColumn('trechos', function ($query, $keyword) {
                $query->whereRaw("(SELECT COUNT(1) FROM medicoes WHERE medicao_servico_id = medicao_servicos.id ) = ?", ["$keyword"]);
            })
            ->orderColumn('trechos','(SELECT COUNT(1) FROM medicoes WHERE medicao_servico_id = medicao_servicos.id ) $1')
            ->editColumn('action', 'medicao_servicos.datatables_actions')
            ->with('aprovador','1')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // Verifica se o usuário é um aprovador de Medições
        $aprovador = WorkflowUsuario::where('user_id',auth()->id())
            ->join('workflow_alcadas','workflow_alcadas.id','workflow_usuarios.workflow_alcada_id')
            ->where('workflow_tipo_id',WorkflowTipo::MEDICAO)
            ->count();

        $medicaoServicos = MedicaoServico::query()
            ->select([
                'medicao_servicos.id',
                'contratos.id as contrato_id',
                'fornecedores.nome as fornecedor',
                DB::raw("CONCAT(insumos.codigo, ' - ',insumos.nome) as insumo"),
                DB::raw("CONCAT(servicos.codigo,' - ', servicos.nome) as apropriacao"),
                'medicao_servicos.periodo_inicio',
                'medicao_servicos.periodo_termino',
                'medicao_servicos.qtd_funcionarios',
                'medicao_servicos.qtd_ajudantes',
                'medicao_servicos.descontos',
                'medicao_servicos.created_at',
                DB::raw("'".$aprovador."' as aprovador"),
                'users.name',
                DB::raw('(SELECT COUNT(1) FROM medicoes WHERE medicao_servico_id = medicao_servicos.id ) as trechos'),
                'medicao_servicos.finalizado',
                'medicao_servicos.aprovado',
                'obras.nome',
                DB::raw('(
                            (
                                SELECT SUM(medicoes.qtd) 
                                FROM medicoes 
                                WHERE medicoes.medicao_servico_id = medicao_servicos.id 
                            ) 
                            * 
                            contrato_itens.valor_unitario
                        ) as soma')
            ])
            ->join('users','users.id','medicao_servicos.user_id')
            ->join('contrato_item_apropriacoes','contrato_item_apropriacoes.id','medicao_servicos.contrato_item_apropriacao_id')
            ->join('insumos','insumos.id','contrato_item_apropriacoes.insumo_id')
            ->join('servicos','servicos.id','contrato_item_apropriacoes.servico_id')
            ->join('contrato_itens','contrato_itens.id','contrato_item_apropriacoes.contrato_item_id')
            ->join('contratos','contratos.id','contrato_itens.contrato_id')
            ->join('obras','obras.id','contratos.obra_id')
            ->join('fornecedores','fornecedores.id','contratos.fornecedor_id')
        ;
        if(request()->segment(count(request()->segments()))=='create' || request()->segment(count(request()->segments()))=='edit'){
            $medicaoServicos->where('medicao_servicos.finalizado','1')
                ->whereRaw('NOT EXISTS(
                    SELECT 1 FROM 
                    medicao_boletim_medicao_servico
                    WHERE medicao_boletim_medicao_servico.medicao_servico_id = medicao_servicos.id
                )');

        }

        return $this->applyScopes($medicaoServicos);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        $buttons = [];
        if(request()->segment(count(request()->segments()))!='create'&& request()->segment(count(request()->segments()))!='edit'){
            $buttons = [
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
            ];
        }
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax('')
            ->parameters([
                'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if(((col+1)<max) && (col>2) || (col==0) ){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'placeholder\',\'Filtrar...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }else if(col==1){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'id\',\'filtro_obra\');
                            $(input).attr(\'placeholder\',\'Obra...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }else if(col==2){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'id\',\'filtro_contrato\');
                            $(input).attr(\'placeholder\',\'Contrato...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }
                    });
                }' ,
                "pageLength"=> (request()->segment(count(request()->segments()))!='create'&& request()->segment(count(request()->segments()))!='edit' ? 10 : 100),
                'dom' => 'Bfrltip',
                'scrollX' => false,
                'language'=> [
                    "url"=> "/vendor/datatables/Portuguese-Brasil.json"
                ],
                'buttons' => $buttons
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        $colunas = [];
        if(request()->segment(count(request()->segments()))=='create' || request()->segment(count(request()->segments()))=='edit'){
            $colunas = [
                '#' => ['name' => 'id', 'data' => 'id', 'width'=>'5%'],
                'obra' => ['name' => 'obras.nome', 'data' => 'nome', 'width'=>'5%'],
                'contrato' => ['name' => 'contratos.id', 'data' => 'contrato_id', 'width'=>'5%'],
                'fornecedor' => ['name' => 'fornecedores.nome', 'data' => 'fornecedor'],
                'insumo' => ['name' => 'insumo', 'data' => 'insumo'],
                'apropriação' => ['name' => 'apropriacao', 'data' => 'apropriacao'],
                'data_medição' => ['name' => 'created_at', 'data' => 'created_at'],
                'período_início' => ['name' => 'periodo_inicio', 'data' => 'periodo_inicio'],
                'período_término' => ['name' => 'periodo_termino', 'data' => 'periodo_termino'],
                'usuário' => ['name' => 'users.name', 'data' => 'name'],
                'trechosMedidos' => ['name' => 'trechos', 'data' => 'trechos', 'width'=>'5%'],
                'valorMedido' => ['name' => 'soma', 'data' => 'soma', 'width'=>'5%'],
                'action' => ['title' => 'Selecionar', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
            ];
        }else{
            $colunas = [
                '#' => ['name' => 'id', 'data' => 'id', 'width'=>'5%'],
                'obra' => ['name' => 'obras.nome', 'data' => 'nome', 'width'=>'5%'],
                'contrato' => ['name' => 'contratos.id', 'data' => 'contrato_id', 'width'=>'5%'],
                'fornecedor' => ['name' => 'fornecedores.nome', 'data' => 'fornecedor'],
                'insumo' => ['name' => 'insumo', 'data' => 'insumo'],
                'apropriação' => ['name' => 'apropriacao', 'data' => 'apropriacao'],
                'data_medição' => ['name' => 'created_at', 'data' => 'created_at'],
                'período_início' => ['name' => 'periodo_inicio', 'data' => 'periodo_inicio'],
                'período_término' => ['name' => 'periodo_termino', 'data' => 'periodo_termino'],
                'usuário' => ['name' => 'users.name', 'data' => 'name'],
                'trechosMedidos' => ['name' => 'trechos', 'data' => 'trechos', 'width'=>'5%'],
                'situação' => ['name' => 'finalizado', 'data' => 'finalizado', 'width'=>'5%'],
                'action' => ['title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
            ];
        }
        return $colunas;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'medicaoServicos';
    }
}
