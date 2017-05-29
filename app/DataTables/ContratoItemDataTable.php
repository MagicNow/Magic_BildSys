<?php

namespace App\DataTables;

use App\Models\ContratoItem;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ContratoStatus;
use App\Models\Contrato;
use App\Repositories\WorkflowAprovacaoRepository;

class ContratoItemDataTable extends DataTable
{

    /**
     * @var int Id do Contrato
     */
    private $contrato;

    public function setContrato(Contrato $contrato)
    {
        $this->contrato = $contrato;

        return $this;
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $datatables = $this->datatables
            ->eloquent($this->query())
            ->addColumn('info', function($item) {
                return view('contratos.itens_datatables_info', compact('item'))->render();
            })
            ->editColumn('qtd', function($item) {
                return float_to_money($item->qtd, '');
            })
            ->editColumn('valor_total', function($item) {
                return float_to_money($item->valor_total);
            });

        if($this->contrato->isStatus(ContratoStatus::APROVADO)) {
            $datatables->addColumn('action', function($item) {
                $workflowAprovacao = [];

                if(!$item->aprovado) {
                    $mod = $item->modificacoes()
                        ->where('contrato_status_id', ContratoStatus::EM_APROVACAO)
                        ->first();

                    $workflowAprovacao = WorkflowAprovacaoRepository::verificaAprovacoes(
                        'ContratoItemModificacao',
                        $mod->id,
                        auth()->user()
                    );

                }

                return view(
                    'contratos.itens_datatables_action',
                    compact('item', 'workflowAprovacao')
                )
                ->render();
            });
        }

        return $datatables->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     */
    public function query()
    {
        $request = $this->request();
        $query = ContratoItem::query()
            ->with(['modificacoes' => function($query) {
                $query->where('contrato_status_id', ContratoStatus::APROVADO);
            }])
            ->select([
                'contrato_itens.*',
                'insumos.codigo as insumo_codigo',
                'insumos.nome as insumo_nome',
                'insumos.unidade_sigla as insumo_unidade',
                DB::raw('
                   (SELECT
                        CONCAT(codigo, \' - \', nome)
                            FROM servicos
                        WHERE ordem_de_compra_itens.servico_id = servicos.id) AS servico'
                ),
            ])
            ->join('insumos', 'insumos.id', 'contrato_itens.insumo_id')
            ->join(
                'oc_item_qc_item',
                'contrato_itens.qc_item_id',
                'oc_item_qc_item.qc_item_id'
            )
            ->join(
                'ordem_de_compra_itens',
                'ordem_de_compra_itens.id',
                'oc_item_qc_item.ordem_de_compra_item_id'
            )
            ->where('contrato_itens.contrato_id', $this->contrato->id)
            ->groupBy('contrato_itens.id');

        return $this->applyScopes($query);
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
                'order' => [],
                'dom' => 'Blfrtip',
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
    protected function getColumns()
    {
        $columns = [
            'info' => [
                'searchable' => false,
                'orderable'  => false,
                'printable'  => false,
                'exportable' => false,
                'title'      => '#'
            ],
            'insumo_codigo' => [
                'data'  => 'insumo_codigo',
                'name'  => 'insumos.codigo',
                'title' => 'Cod. Insumo',
            ],
            'insumo_nome' => [
                'data'  => 'insumo_nome',
                'name'  => 'insumos.nome',
                'title' => 'Descrição',
            ],
            'insumo_unidade' => [
                'data'  => 'insumo_unidade',
                'name'  => 'insumos.unidade_sigla',
                'title' => 'Un',
            ],
            'qtd' => [
                'data'  => 'qtd',
                'name'  => 'contrato_itens.qtd',
                'title' => 'Qtd'
            ],
            'valor_total' => [
                'data'  => 'valor_total',
                'name'  => 'contrato_itens.valor_total',
                'title' => 'Total'
            ],
        ];

        if($this->contrato->isStatus(ContratoStatus::APROVADO)) {
            $columns['action'] = [
                'searchable' => false,
                'orderable'  => false,
                'printable'  => false,
                'exportable' => false,
            ];
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
        return 'contratoitemdatatables_' . time();
    }
}
