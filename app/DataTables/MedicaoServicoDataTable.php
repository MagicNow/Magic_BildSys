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
                return '<div class="text-right">'.($obj->soma ? float_to_money($obj->soma)  : 'R$ 0,00').'</div>';
            })
            ->editColumn('descontos', function ($obj){
                return '<div class="text-right">'.($obj->descontos ? float_to_money($obj->descontos)  : 'R$ 0,00').'</div>';
            })
            ->editColumn('qtd_medida', function ($obj){
                return '<div class="text-right">'.($obj->qtd_medida ? float_to_money($obj->qtd_medida,'')  : '0,00').'</div>';
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
            ->filterColumn('qtd_medida', function ($query, $keyword) {
                $query->whereRaw("(
                            SELECT SUM(medicoes.qtd) 
                            FROM medicoes 
                            WHERE medicoes.medicao_servico_id = medicao_servicos.id 
                        ) LIKE ?", ["%$keyword%"]);
            })
            ->filterColumn('soma', function ($query, $keyword) {
                $query->whereRaw("(
                            (
                                SELECT SUM(medicoes.qtd) 
                                FROM medicoes 
                                WHERE medicoes.medicao_servico_id = medicao_servicos.id 
                            ) 
                            * 
                            contrato_itens.valor_unitario
                        ) LIKE ?", ["%$keyword%"]);
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
                'insumos.codigo',
                DB::raw('insumos.nome as insumo'),
                'insumos.unidade_sigla',
                DB::raw("CONCAT(servicos.codigo,' - ', servicos.nome) as apropriacao"),
                'medicao_servicos.periodo_inicio',
                'medicao_servicos.periodo_termino',
                'medicao_servicos.qtd_funcionarios',
                'medicao_servicos.qtd_ajudantes',
                'medicao_servicos.descontos',
                'medicao_servicos.created_at',
                'insumos.unidade_sigla as unidade',
                'contrato_itens.qtd as qtd_total_insumo',
                'contrato_itens.valor_unitario',
                'contrato_itens.valor_total',
                DB::raw('(contrato_itens.valor_total - IFNULL( (
                    SELECT SUM(medicoes.qtd)
                    FROM medicoes 
                    JOIN medicao_servicos MS ON MS.id = medicoes.medicao_servico_id
                    WHERE 
                        EXISTS(
                            SELECT 1 FROM medicao_boletim_medicao_servico
                            JOIN medicao_boletins MB ON MB.id = medicao_boletim_medicao_servico.medicao_boletim_id
                            WHERE 
                                medicao_boletim_medicao_servico.medicao_servico_id = MS.id
                                AND MB.medicao_boletim_status_id > 1
                        )
                        AND EXISTS(
                            SELECT 1 FROM 
                            contrato_item_apropriacoes CAP
                            WHERE CAP.contrato_item_id = contrato_itens.id
                            AND CAP.id = MS.contrato_item_apropriacao_id
                        )
                    ),0) * contrato_itens.valor_unitario
                ) as saldo'),
                DB::raw('(
                            SELECT SUM(medicoes.qtd) 
                            FROM medicoes 
                            WHERE medicoes.medicao_servico_id = medicao_servicos.id 
                        ) as qtd_medida'),
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
                'responsive' => 'true',
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
                // Ordena para que inicialmente carregue os mais novos
                'order' => [
                    0,
                    'desc'
                ],
                'language'=> [
                    "url"=> asset("vendor/datatables/Portuguese-Brasil.json")
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
                'código' => ['name' => 'codigo', 'data' => 'codigo'],
                'insumo' => ['name' => 'insumo', 'data' => 'insumo'],
                'Un&period; De Medida' => ['name' => 'unidade_sigla', 'data' => 'unidade_sigla'],
                'apropriação' => ['name' => 'apropriacao', 'data' => 'apropriacao'],
                'data_medição' => ['name' => 'created_at', 'data' => 'created_at'],
                'usuário' => ['name' => 'users.name', 'data' => 'name'],
                'quantidadeMedida' => ['name' => 'qtd_medida', 'data' => 'qtd_medida', 'width'=>'5%'],
                'descontos' => ['name' => 'descontos', 'data' => 'descontos', 'width'=>'5%'],
                'valorMedido' => ['name' => 'soma', 'data' => 'soma', 'width'=>'5%'],
                'action' => ['title' => 'Selecionar', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
            ];
        }else{
            $colunas = [
                '#' => ['name' => 'id', 'data' => 'id', 'width'=>'5%'],
                'obra' => ['name' => 'obras.nome', 'data' => 'nome', 'width'=>'5%'],
                'contrato' => ['name' => 'contratos.id', 'data' => 'contrato_id', 'width'=>'5%'],
                'fornecedor' => ['name' => 'fornecedores.nome', 'data' => 'fornecedor'],
                'código' => ['name' => 'codigo', 'data' => 'codigo'],
                'insumo' => ['name' => 'insumo', 'data' => 'insumo'],
                'Un&period; De Medida' => ['name' => 'unidade_sigla', 'data' => 'unidade_sigla'],
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
