<?php

namespace App\DataTables;

use App\Models\Contrato;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ContratoDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('created_at', function ($contrato) {
                return $contrato->created_at
                    ? $contrato->created_at->format('d/m/Y')
                    : '';
            })
            ->editColumn('valor_total', function ($contrato) {
                return float_to_money($contrato->valor_total);
            })
            ->addColumn('action', 'contratos.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = Contrato::query();

        $query->select([
            'contratos.id',
            'contratos.created_at',
            'contratos.valor_total',
            'fornecedores.nome as fornecedor',
            'obras.nome as obra',
            'contrato_status.nome as status'
        ])
        ->join('obras', 'obras.id', 'contratos.obra_id')
        ->join('fornecedores', 'fornecedores.id', 'contratos.fornecedor_id')
        ->join('contrato_status', 'contrato_status.id', 'contratos.contrato_status_id');

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
            ->addAction(['width' => '80px'])
            ->parameters([
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
        return [
            'id' => ['name' => 'id', 'data' => 'id', 'title' => 'N° do Contrato'],
            'created_at' => ['name' => 'created_at', 'data' => 'created_at', 'title' => 'Data'],
            'fornecedor' => ['name' => 'fornecedores.nome', 'data' => 'fornecedor'],
            'obra' => ['name' => 'obras.nome', 'data' => 'obra'],
            'valor_total' => ['name' => 'valor_total', 'data' => 'valor_total', 'title' => 'Saldo'],
            'status' => ['name' => 'status.nome', 'data' => 'status'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'contratodatatables_' . time();
    }
}
