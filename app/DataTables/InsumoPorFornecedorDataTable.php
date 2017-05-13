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
                'insumo_id' => $item->insumo->id,
                'qc_item_id' => $item->id
            ];
        });

        return $collection->map(function($insumo) {
            $this->qcFornecedores->each(function($qcFornecedor) use (&$insumo) {
                $valor = $qcFornecedor->itens
                    ->where('qc_item_id', $insumo['qc_item_id'])
                    ->first()
                    ->valor_total ?: 0;

                $insumo[$qcFornecedor->fornecedor->nome . '||' . $qcFornecedor->id] = float_to_money($valor);
            });

            return $insumo;
        });
    }

    protected function getColumns()
    {
        $x = array_filter(
            array_keys($this->query()->first()),
            function($item) {
                return !in_array($item, ['qc_item_id', 'insumo_id']);
            }
        );

        return array_reduce($x, function($columns, $column) {
            if($column != 'insumo') {
                list($fornecedor, $id) = explode('||', $column);

                $title = $fornecedor . '
                    <button class="btn btn-xs btn-default btn-flat pull-right" data-qcfornecedor="' . $id . '">
                        <i class="fa fa-info-circle"></i>
                    </button>
                ';
            } else {
                $title = $column;
            }

            $columns[$column] = [
                'data'  => $column,
                'name' => $column,
                'title' => $title
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