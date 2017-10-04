<?php

namespace App\DataTables\Admin;

use App\Models\NomeclaturaMapa;
use Form;
use Yajra\Datatables\Services\DataTable;

class NomeclaturaMapaDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.nomeclatura_mapas.datatables_actions')
            ->editColumn('tipo', function($obj){
                if($obj->tipo==1){
                    if(!$obj->apenas_cartela && !$obj->apenas_unidade){
                        return 'Estrutura';
                    }
                    return 'Bloco';
                }
                if($obj->tipo==2){
                    if(!$obj->apenas_cartela && !$obj->apenas_unidade){
                        return 'Pavimento';
                    }
                    return 'Linha';

                }
                if($obj->tipo==3){
                    if(!$obj->apenas_cartela && !$obj->apenas_unidade){
                        return 'Trecho';
                    }
                    return 'Coluna';
                }
            })
            ->filterColumn('tipo', function($query, $keyword){
                if(strlen($keyword)){
                    $letra = strtolower( substr($keyword,0,1) );
                    if($letra =='e'||$letra =='b'){
                        $tipo = 1;
                        if($letra =='b'){
                            $query->where(function ($subquery){
                                $subquery->where('apenas_unidade',1);
                                $subquery->orWhere('apenas_cartela',1);
                            });
                        }else{
                            $query->where(function ($subquery){
                                $subquery->where('apenas_unidade',0);
                                $subquery->where('apenas_cartela',0);
                            });
                        }
                    }
                    if($letra =='p'||$letra =='l'){
                        $tipo = 2;
                        if($letra =='l'){
                            $query->where(function ($subquery){
                                $subquery->where('apenas_unidade',1);
                                $subquery->orWhere('apenas_cartela',1);
                            });
                        }else{
                            $query->where(function ($subquery){
                                $subquery->where('apenas_unidade',0);
                                $subquery->where('apenas_cartela',0);
                            });
                        }
                    }
                    if($letra =='t'||$letra =='c'){
                        $tipo = 3;
                        if($letra =='c'){
                            $query->where(function ($subquery){
                                $subquery->where('apenas_unidade',1);
                                $subquery->orWhere('apenas_cartela',1);
                            });
                        }else{
                            $query->where(function ($subquery){
                                $subquery->where('apenas_unidade',0);
                                $subquery->where('apenas_cartela',0);
                            });
                        }
                    }
                    $query->where('tipo',$tipo);
                }
            })
            ->editColumn('apenas_unidade', function($obj){
                return '<span class="label label-'.(intval($obj->apenas_unidade)?'success':'danger').'">'. (intval($obj->apenas_unidade)?'SIM':'NÃO') .'</span>';
            })
            ->filterColumn('apenas_unidade', function($query, $keyword){
                if(strlen($keyword)){
                    $letra = strtolower( substr($keyword,0,1) );
                    if($letra =='s'){
                        $valor = 1;
                    }
                    if($letra =='n'){
                        $valor = 0;
                    }
                    $query->where('apenas_unidade',$valor);
                }
            })
            ->editColumn('apenas_cartela', function($obj){
                return '<span class="label label-'.($obj->apenas_cartela?'success':'danger').'">'.
                ($obj->apenas_cartela?'SIM':'NÃO')
                    .'</span>';
            })
            ->filterColumn('apenas_cartela', function($query, $keyword){
                if(strlen($keyword)){
                    $letra = strtolower( substr($keyword,0,1) );
                    if($letra =='s'){
                        $valor = 1;
                    }
                    if($letra =='n'){
                        $valor = 0;
                    }
                    $query->where('apenas_cartela',$valor);
                }
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $nomeclaturaMapas = NomeclaturaMapa::query();

        return $this->applyScopes($nomeclaturaMapas);
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
//            ->addAction(['width' => '10%'])
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
                'dom' => 'Bfrtip',
                'scrollX' => false,
                'language'=> [
                    "url"=> asset("vendor/datatables/Portuguese-Brasil.json")
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
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'tipo' => ['name' => 'tipo', 'data' => 'tipo'],
            'apenas_cartela' => ['name' => 'apenas_cartela', 'data' => 'apenas_cartela'],
            'apenas_unidade' => ['name' => 'apenas_unidade', 'data' => 'apenas_unidade'],
            'action' => ['name' => 'Ações', 'title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'nomeclaturaMapas';
    }
}
