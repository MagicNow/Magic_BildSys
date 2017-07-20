<?php

namespace App\DataTables;

use App\Models\Medicao;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class MedicaoDataTable extends DataTable
{
    protected $medicao_servico_id;

    public function servico($id){
        $this->medicao_servico_id = $id;
        return $this;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('percentual', function ($obj){
                return '<div class="text-right">'.number_format($obj->percentual,2,',','.').'</div>';
            })
            ->editColumn('qtd', function ($obj){
                return '<div class="text-right">'.number_format($obj->qtd,2,',','.').'</div>';
            })
            ->editColumn('obs', function ($obj){
                return '<div class="text-left">'.str_limit($obj->obs,50).'</div>';
            })
            ->editColumn('created_at', function($obj){
                return $obj->created_at ? with(new\Carbon\Carbon($obj->created_at))->format('d/m/Y H:i') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(medicao_servicos.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('local', function ($query, $keyword) {
                $query->whereRaw("CONCAT(E.nome,' - ',P.nome,' - ',T.nome) like ?", ["%$keyword%"]);
            })
            ->filterColumn('qtd', function ($query, $keyword) {
                $keyword = str_replace(',','.', str_replace('.','',$keyword));
                $query->whereRaw("medicoes.qtd like ?", ["%$keyword%"]);
            })
            ->filterColumn('percentual', function ($query, $keyword) {
                $keyword = str_replace(',','.', str_replace('.','',$keyword));
                $query->whereRaw("((medicoes.qtd/mc_medicao_previsoes.qtd)*100) like ?", ["%$keyword%"]);
            })
            ->editColumn('action', 'medicoes.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $medicaos = Medicao::query()
            ->select([
                'medicoes.id',
                'users.name as user',
                'medicoes.qtd',
                'medicoes.obs',
                'medicoes.aprovado',
                'medicoes.medicao_servico_id',
                'medicao_servicos.finalizado as medicao_servico_finalizado',
                'medicoes.created_at',
                DB::raw('(medicoes.qtd/mc_medicao_previsoes.qtd)*100 as percentual'),
                DB::raw("CONCAT(E.nome,' - ',P.nome,' - ',T.nome) as local"),
            ])
        ->join('users','users.id','medicoes.user_id')
        ->join('mc_medicao_previsoes','mc_medicao_previsoes.id','medicoes.mc_medicao_previsao_id')
        ->join('memoria_calculo_blocos','memoria_calculo_blocos.id','mc_medicao_previsoes.memoria_calculo_bloco_id')
        ->join('nomeclatura_mapas as E','memoria_calculo_blocos.estrutura','E.id')
        ->join('nomeclatura_mapas as P','memoria_calculo_blocos.pavimento','P.id')
        ->join('nomeclatura_mapas as T','memoria_calculo_blocos.trecho','T.id')
        ->leftJoin('medicao_servicos','medicao_servicos.id','medicoes.medicao_servico_id')
        ;

        if($this->medicao_servico_id){
            $medicaos->where('medicao_servico_id',$this->medicao_servico_id);
        }

        return $this->applyScopes($medicaos);
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
                    'reload',
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
            '#' => ['name' => 'id', 'data' => 'id','width'=>'5%'],
            'local' => ['name' => 'local', 'data' => 'local'],
            'quantidade' => ['name' => 'qtd', 'data' => 'qtd'],
            'percentual' => ['name' => 'percentual', 'data' => 'percentual'],
            'usuário' => ['name' => 'users.name', 'data' => 'user'],
            'obs' => ['name' => 'obs', 'data' => 'obs'],
            'data_medição' => ['name' => 'created_at', 'data' => 'created_at'],
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
        return 'medicaos';
    }
}
