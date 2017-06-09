<?php

namespace App\DataTables;

use App\Models\QcFornecedor;
use App\Models\QuadroDeConcorrencia;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Database\Eloquent\Collection;

class InsumoPorFornecedorDataTable extends DataTable
{
    /**
     * @var QuadroDeConcorrencia
     */
    private $quadro;

    /**
     * @var Collection<QcFornecedor>
     */
    private $qcFornecedores;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {

        return $this->datatables
            ->collection($this->query())
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $collection = $this->quadro->itens->map(function($item) {
            return [
                'insumo' => $item->insumo->nome,
                'qtd' => '',
                'insumo_id' => $item->insumo->id,
                'qc_item_id' => $item->id,
                'valor_unitario_calculo' => $item->ordemDeCompraItens->sortBy('valor_unitario')->first() ? $item->ordemDeCompraItens->sortBy('valor_unitario')->first()->valor_unitario : 'R$ 0,00',
                'valor unitário oi' => $item->ordemDeCompraItens->sortBy('valor_unitario')->first() ? float_to_money(floatval($item->ordemDeCompraItens->sortBy('valor_unitario')->first()->valor_unitario)) : 'R$ 0,00',
                'valor total oi' => ''
            ];
        });

        return $collection->map(function($insumo) {
            $this->qcFornecedores->each(function($qcFornecedor) use (&$insumo) {
                 $item_fornecedor = $qcFornecedor->itens
                    ->where('qc_item_id', $insumo['qc_item_id'])
                    ->first();

                $valor = $item_fornecedor ? $item_fornecedor->valor_total : 0;
                $qtd_comprada = $item_fornecedor ? $item_fornecedor->qtd : 0;
                $valor_comprado_oi = $insumo['valor_unitario_calculo'] * $qtd_comprada;

                $insumo[str_replace('.', '*dot*',$qcFornecedor->fornecedor->nome . '||' . $qcFornecedor->id)] = float_to_money($valor);
                $insumo['qtd'] =  number_format($qtd_comprada, 2, ',', '.');
                $insumo['valor total oi'] = float_to_money($valor_comprado_oi);
            });

            return $insumo;
        });
    }

    protected function getColumns()
    {
        $x = array_filter(
            array_keys($this->query()->first()),
            function($item) {
                return !in_array($item, ['qc_item_id', 'insumo_id', 'valor_unitario_calculo']);
            }
        );

        return array_reduce($x, function($columns, $column) {
            if($column != 'insumo' && $column != 'valor unitário oi' && $column != 'qtd' && $column != 'valor total oi') {
                list($fornecedor, $id) = explode('||', $column);

                $title = $fornecedor . '
                    <button class="btn btn-xs btn-default btn-flat pull-right" data-qcfornecedor="' . $id . '">
                        <i class="fa fa-info-circle"></i>
                    </button>
                ';
            } else {
                $title = $column;
            }

            $fornecedor = isset($fornecedor) ? $fornecedor : $column;

            $columns[$fornecedor] = [
                'data'  => $column,
                'name'  => $fornecedor,
                'title' => str_replace('*dot*', '.', $title)
            ];

            return $columns;
        }, []);
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
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'placeholder\',\'Filtrar...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
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
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'insumoporfornecedordatatables_' . time();
    }

    public function setQuadroDeConcorrencia(QuadroDeConcorrencia $quadro)
    {
        $this->quadro = $quadro;

        return $this;
    }

    public function setQcFornecedores(Collection $qcFornecedores)
    {
        $this->qcFornecedores = $qcFornecedores;

        return $this;
    }

}
