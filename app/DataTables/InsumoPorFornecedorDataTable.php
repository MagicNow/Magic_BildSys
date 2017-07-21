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
                'qntd do QC' => '',
                'insumo_id' => $item->insumo->id,
                'qc_item_id' => $item->id,
                'valor_unitario_calculo' => $item->ordemDeCompraItens->sortBy('valor_unitario')->first() ? $item->ordemDeCompraItens->sortBy('valor_unitario')->first()->valor_unitario : 'R$ 0,00',
                'valor unitário do orçamento' => $item->ordemDeCompraItens->sortBy('valor_unitario')->first() ? float_to_money(floatval($item->ordemDeCompraItens->sortBy('valor_unitario')->first()->valor_unitario)) : 'R$ 0,00',
                'Valor total previsto' => ''
            ];
        });

        $collection->push([
                'insumo'  => 'FRETE',
                'qntd do QC'  => '',
                'insumo_id' => '',
                'qc_item_id' => '',
                'valor_unitario_calculo' => '',
                'valor unitário do orçamento' => '',
                'Valor total previsto' => '',
            ]);

//        dd($collection);

        return $collection->map(function($insumo) {
            $this->qcFornecedores->each(function($qcFornecedor) use (&$insumo) {
                 $item_fornecedor = $qcFornecedor->itens
                    ->where('qc_item_id', $insumo['qc_item_id'])
                    ->first();

                    $valor = $item_fornecedor ? float_to_money($item_fornecedor->valor_total) : '<span style="color:red">DECLINED</span>';
                    $qtd_comprada = $item_fornecedor ? $item_fornecedor->qtd : 0;
                    
                    $insumoValorUnitario = $insumo['valor_unitario_calculo'];
                    if(!$insumoValorUnitario){
                        $insumoValorUnitario = 0;
                    }
                    $valor_frete = $qcFornecedor->valor_frete ? $qcFornecedor->valor_frete : 0;
                    $valor_comprado_oi = doubleval($insumo['valor_unitario_calculo']) * $qtd_comprada;
                    if($qcFornecedor->fornecedor) {
                        if(!$qcFornecedor->desistencia_motivo_id || !$qcFornecedor->desistencia_texto) {
                            $insumo[str_replace('.',
                                '*dot*',
                                $qcFornecedor->fornecedor->nome . '||' . $qcFornecedor->id)] = $valor;
                            if($insumo['insumo'] === 'FRETE') {
                                $insumo[str_replace('.',
                                    '*dot*',
                                    $qcFornecedor->fornecedor->nome . '||' . $qcFornecedor->id)] = 'R$ '. $valor_frete;
                            }
                        }else{
                            if($insumo['insumo'] != 'FRETE') {
                                $insumo[str_replace('.',
                                    '*dot*',
                                    $qcFornecedor->fornecedor->nome . '||' . $qcFornecedor->id)] = '<span style="color:red">DECLINED</span>';
                            } else {
                                $insumo[str_replace('.',
                                    '*dot*',
                                    $qcFornecedor->fornecedor->nome . '||' . $qcFornecedor->id)] = null;
                            }
                        }
                    }
                    $insumo['qntd do QC'] =  number_format($qtd_comprada, 2, ',', '.');
                    $insumo['Valor total previsto'] = float_to_money($valor_comprado_oi);
                    if($insumo['insumo'] === 'FRETE') {
                        $insumo['qntd do QC'] = '';
                        $insumo['Valor total previsto'] = '';
                        $insumo['valor unitário do orçamento'] = '';
                    }

            });
//            dd($insumo);
            return $insumo;
        });
    }

    protected function getColumns()
    {
        $x = array_filter(
            array_keys($this->query()->first()),
            function($item) {
                return !in_array($item, ['qc_item_id', 'insumo_id', 'valor_unitario_calculo', 'frete']);
            }
        );

        return array_reduce($x, function($columns, $column) {
            $excluded = ['insumo', 'valor unitário do orçamento', 'qntd do QC', 'Valor total previsto', 'frete'];
            if(!in_array($column, $excluded)) {
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
