<?php

namespace App\DataTables;

use App\Models\MedicaoServico;
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
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
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
                'users.name',
                DB::raw('(SELECT COUNT(1) FROM medicoes WHERE medicao_servico_id = medicao_servicos.id ) as trechos'),
                'medicao_servicos.finalizado',
                'medicao_servicos.aprovado',
            ])
            ->join('users','users.id','medicao_servicos.user_id')
            ->join('contrato_item_apropriacoes','contrato_item_apropriacoes.id','medicao_servicos.contrato_item_apropriacao_id')
            ->join('insumos','insumos.id','contrato_item_apropriacoes.insumo_id')
            ->join('servicos','servicos.id','contrato_item_apropriacoes.servico_id')
            ->join('contrato_itens','contrato_itens.id','contrato_item_apropriacoes.contrato_item_id')
            ->join('contratos','contratos.id','contrato_itens.contrato_id')
            ->join('fornecedores','fornecedores.id','contratos.fornecedor_id')
        ;

        return $this->applyScopes($medicaoServicos);
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
            '#' => ['name' => 'id', 'data' => 'id', 'width'=>'5%'],
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
