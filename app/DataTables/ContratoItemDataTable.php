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
        $calc_aliq = function ($type) {
            return function ($item) use ($type) {
                $value = ($item->{'aliq_' . $type} ?: 0) / 100;
                return float_to_money($value * 100, '') . '%';
            };
        };

        $datatables = $this->datatables
            ->eloquent($this->query())
            ->editColumn('qtd', function ($item) {
                return $item->qtd_formatted;
            })
            ->editColumn('valor_total', function ($item) {
                return float_to_money($item->valor_total);
            })
            ->editColumn('aliq_irrf', $calc_aliq('irrf'))
            ->editColumn('aliq_inss', $calc_aliq('inss'))
            ->editColumn('aliq_pis', $calc_aliq('pis'))
            ->editColumn('aliq_csll', $calc_aliq('csll'))
            ->editColumn('aliq_cofins', $calc_aliq('cofins'));

        if ($this->contrato->isStatus(ContratoStatus::APROVADO, ContratoStatus::ATIVO)) {
            $datatables->addColumn('action', function ($item) {
                return view(
                    'contratos.itens_datatables_action',
                    compact('item')
                )
                ->render();
            });

            $datatables->addColumn('info', function ($item) {
                $item->load(['modificacoes' => function ($query) {
                    return $query->where('contrato_status_id', ContratoStatus::APROVADO);
                }]);

                $reapropriacoes_dos_itens = $item->reapropriacoes->filter(function ($re) {
                    return is_null($re->contrato_item_reapropriacao_id) && !is_null($re->ordem_de_compra_item_id);
                })->pluck('ordem_de_compra_item_id')->unique();

                $reapropriacoes_de_reapropriacoes = $item->reapropriacoes->filter(function ($re) {
                    return $re->reapropriacoes->isNotEmpty();
                });

                $reprovado = false;
                $workflow = false;
                $lastMod = $item->modificacoes()->orderBy('created_at', 'desc')->first();

                if($lastMod && $lastMod->contrato_status_id === ContratoStatus::REPROVADO) {
                    $reprovado = $lastMod;
                    $workflow = $lastMod->aprovacoes()->orderBy('created_at', 'desc')->first();
                }

                return view(
                    'contratos.itens_datatables_info',
                    compact(
                        'item',
                        'reapropriacoes_dos_itens',
                        'reapropriacoes_de_reapropriacoes',
                        'reprovado',
                        'workflow'
                    ))
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
            ->select([
                'contrato_itens.*',
                'insumos.nome as insumo_nome',
                'insumos.unidade_sigla as insumo_unidade',
                'insumos.aliq_irrf',
                'insumos.aliq_inss',
                'insumos.aliq_pis',
                'insumos.aliq_cofins',
                'insumos.aliq_csll',
                DB::raw('CONCAT(contrato_itens.qtd, \' \', insumos.unidade_sigla) as qtd_unidade'),
                DB::raw('
                   (SELECT
                        CONCAT(codigo, \' - \', nome)
                            FROM servicos
                        WHERE ordem_de_compra_itens.servico_id = servicos.id) AS servico'
                ),
            ])
            ->join('insumos', 'insumos.id', 'contrato_itens.insumo_id')
            ->leftJoin('contrato_item_modificacoes', 'contrato_itens.id', 'contrato_item_modificacoes.contrato_item_id')
            ->leftJoin(
                'oc_item_qc_item',
                'contrato_itens.qc_item_id',
                'oc_item_qc_item.qc_item_id'
            )
            ->leftJoin(
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
        $columns = [];
        if ($this->contrato->isStatus(ContratoStatus::APROVADO, ContratoStatus::ATIVO)) {
            $columns['info'] = [
                'searchable' => false,
                'orderable'  => false,
                'printable'  => false,
                'exportable' => false,
                'title'      => '#',
                'width'      => '10%'
            ];
        }

        $columns = array_merge($columns, [
            'insumo_nome' => [
                'data'  => 'insumo_nome',
                'name'  => 'insumos.nome',
                'title' => 'Descrição',
            ],
            'qtd_unidade' => [
                'data'  => 'qtd_unidade',
                'name'  => 'contrato_itens.qtd',
                'title' => 'Qtd'
            ],
            'valor_total' => [
                'data'  => 'valor_total',
                'name'  => 'contrato_itens.valor_total',
                'title' => 'Total'
            ],
            'aliq_inss' => [
                'title' => 'INSS',
                'visible'=> false,
                'name' => 'aliq_inss',
            ],
            'aliq_pis' => [
                'title' => 'PIS',
                'visible'=> false,
                'name' => 'aliq_pis',
            ],
            'aliq_cofins' => [
                'title' => 'COFINS',
                'visible'=> false,
                'name' => 'aliq_cofins',
            ],
            'aliq_csll' => [
                'title' => 'CSLL',
                'visible'=> false,
                'name' => 'aliq_csll',
            ],
        ]);

        if ($this->contrato->isStatus(ContratoStatus::APROVADO, ContratoStatus::ATIVO)) {
            $columns['action'] = [
                'searchable' => false,
                'orderable'  => false,
                'printable'  => false,
                'exportable' => false,
                'title'      => 'Ações',
                'class'      => 'all',
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
