<?php

namespace App\DataTables\Admin;

use App\Models\InsumoGrupo;
use DB;
use Form;
use Yajra\Datatables\Services\DataTable;

class LembreteDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $insumos_grupos = InsumoGrupo::query()->select([
                'insumo_grupos.id',
                'insumo_grupos.nome',
                DB::raw("(SELECT LEM2.dias_prazo_minimo
                            FROM lembretes LEM2
                            WHERE LEM2.insumo_grupo_id = insumo_grupos.id                            
                            AND LEM2.lembrete_tipo_id = 3
                            AND LEM2.deleted_at IS NULL
                         ) as prazo_minimo_mobilizacao"
                ),
                DB::raw("(SELECT LEM2.dias_prazo_minimo
                                FROM lembretes LEM2
                                WHERE LEM2.insumo_grupo_id = insumo_grupos.id                            
                                AND LEM2.lembrete_tipo_id = 2
                                AND LEM2.deleted_at IS NULL
                             ) as prazo_minimo_negociacao"
                ),
                DB::raw("(SELECT LEM2.dias_prazo_minimo
                                FROM lembretes LEM2
                                WHERE LEM2.insumo_grupo_id = insumo_grupos.id                            
                                AND LEM2.lembrete_tipo_id = 1
                                AND LEM2.deleted_at IS NULL
                             ) as prazo_minimo_start"
                ),
            ])
            ->leftJoin('lembretes', 'lembretes.insumo_grupo_id', '=', 'insumo_grupos.id');

//        $lembretes = Lembrete::query()->select([
//            'lembretes.id',
//            'lembretes.nome',
//            'lembretes.dias_prazo_minimo',
//            'lembrete_tipos.nome as tipo',
//            'insumo_grupos.nome as grupo',
//            'users.name as user',
//        ])
//            ->join('lembrete_tipos','lembrete_tipos.id','lembretes.lembrete_tipo_id')
//            ->join('insumo_grupos','insumo_grupos.id','lembretes.insumo_grupo_id')
//            ->join('users','users.id','lembretes.user_id')
//        ;

        return $this->applyScopes($insumos_grupos);
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
            // ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if((col)<max){
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
            'grupo_insumo' => ['name' => 'insumo_grupos.nome', 'data' => 'nome'],
            'mobilização' => ['name' => 'prazo_minimo_mobilizacao', 'data' => 'prazo_minimo_mobilizacao'],
            'negociação' => ['name' => 'prazo_minimo_negociacao', 'data' => 'prazo_minimo_negociacao'],
            'start' => ['name' => 'prazo_minimo_start', 'data' => 'prazo_minimo_start']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'lembretes';
    }
}
