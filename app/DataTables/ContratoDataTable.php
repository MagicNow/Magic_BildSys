<?php

namespace App\DataTables;

use App\Models\Contrato;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            ->editColumn('status', function($obj){
                return '<i class="fa fa-circle" aria-hidden="true" style="color:'
                    . $obj->status_cor
                    . '"></i> '
                    . $obj->status;
            })
            ->addColumn('action', 'contratos.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
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
            'contrato_status.nome as status',
            'contrato_status.cor as status_cor'
        ])
        ->join('obras', 'obras.id', 'contratos.obra_id')
        ->join('fornecedores', 'fornecedores.id', 'contratos.fornecedor_id')
        ->join('contrato_status', 'contrato_status.id', 'contratos.contrato_status_id')
        ->join('contrato_itens', 'contrato_itens.contrato_id', 'contratos.id')
        ->join('oc_item_qc_item', 'contrato_itens.qc_item_id', 'oc_item_qc_item.qc_item_id')
        ->join('ordem_de_compra_itens', 'ordem_de_compra_itens.id', 'oc_item_qc_item.ordem_de_compra_item_id');

        $request = $this->request();

        if($request->fornecedor_id) {
            $query->where('contratos.fornecedor_id', $request->fornecedor_id);
        }

        if($request->obra_id) {
            $query->where('contratos.obra_id', $request->obra_id);
        }

        if($request->contrato_status_id) {
            $query->where('contratos.contrato_status_id', $request->contrato_status_id);
        }

        if($request->grupo_id) {
            $query->where('ordem_de_compra_itens.grupo_id', $request->grupo_id);
        }

        if($request->subgrupo1_id) {
            $query->where('ordem_de_compra_itens.subgrupo1_id', $request->subgrupo1_id);
        }

        if($request->subgrupo2_id) {
            $query->where('ordem_de_compra_itens.subgrupo2_id', $request->subgrupo2_id);
        }

        if($request->subgrupo3_id) {
            $query->where('ordem_de_compra_itens.subgrupo3_id', $request->subgrupo3_id);
        }

        if($request->servico_id) {
            $query->where('ordem_de_compra_itens.servico_id', $request->servico_id);
        }

        if(!is_null($request->days)) {
            $query->whereDate(
                'contratos.created_at',
                '>=',
                Carbon::now()->subDays($request->days)->toDateString()
            );
        }

        if($request->start) {
            $query->whereDate(
                'contratos.created_at',
                '>=',
                Carbon::createFromFormat('d/m/Y', $request->start)->toDateString()
            );
        }

        if($request->end) {
            $query->whereDate(
                'contratos.created_at',
                '<=',
                Carbon::createFromFormat('d/m/Y', $request->end)->toDateString()
            );
        }

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
