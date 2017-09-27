<?php

namespace App\DataTables\Scopes;

use Yajra\Datatables\Contracts\DataTableScopeContract;

class PagamentoDataTableScope implements DataTableScopeContract
{
    private $contrato_id;

    public function __construct($contrato_id){
        $this->contrato_id = $contrato_id;
    }
    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->where('contrato_id', $this->contrato_id);
    }
}
