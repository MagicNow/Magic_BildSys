<?php

namespace App\DataTables\Admin;

use App\Models\Insumo;
use App\Models\TipoLevantamento;
use Yajra\Datatables\Services\DataTable;

class MascaraPadraoRelacionarInsumoDataTable extends DataTable
{
    protected $mascara_padrao_id;

    public function mp($id){
        $this->mascara_padrao_id = $id;
        return $this;
    }
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.mascara_padrao_estruturas.relacionar_insumos_datatables_actions')
            ->editColumn('coeficiente', function($obj){
                return "<input  type='text' class='form-control money' name='coeficiente_$obj->id'>";
            })
            ->editColumn('indireto', function($obj){
                return "<input type='text' class='form-control money' name='indireto_$obj->id'>";
            })
            ->editColumn('levantamento', function($obj){
                $tipos = TipoLevantamento::whereNull('deleted_at')->get();

                $options = '<option value="">Selecione</option>';
                foreach($tipos as $item){
                    $options .= '<option value="'.$item->id.'"> '.$item->nome.' </option>';
                }
                return '<select class="form-control select2" name="tipo_levantamento_'.$obj->id.'">'.$options.'</select>';
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = Insumo::query()
            ->select([
                'id',
                'nome',
                'codigo'
            ])
            ->whereRaw(
                'id NOT IN
                    (SELECT insumo_id
                        FROM mascara_padrao_insumos
                        JOIN mascara_padrao_estruturas ON mascara_padrao_estruturas.id = mascara_padrao_insumos.mascara_padrao_estrutura_id
                        JOIN mascara_padrao ON mascara_padrao.id = mascara_padrao_estruturas.mascara_padrao_id
                        WHERE mascara_padrao.id = '.$this->mascara_padrao_id.'
                    )
            ')
            ->where('active', 1)
            ->orderBy('nome', 'ASC');

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
                    carregaMoney();
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if((col+4)<max){
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
//                "lengthChange"=> true,
                "pageLength"=> 25,
                'dom' => 'Bfrltip',
                'scrollX' => false,
                'language'=> [
                    "url"=> "/vendor/datatables/Portuguese-Brasil.json"
                ],
                'buttons' => [
                    'reset',
                    'reload',
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
            'Código' => ['name' => 'codigo', 'data' => 'codigo', 'width'=> '5%'],
            'nome' => ['name' => 'nome', 'data' => 'nome', 'width'=> '40%'],
            'Coeficiente' => ['name' => 'coeficiente', 'data' => 'coeficiente', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=> '5%' ],
            'Indireto' => ['name' => 'indireto', 'data' => 'indireto', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=> '5%'],
            'levantamento' => ['name' => 'levantamento', 'title' => 'levantamento', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=> '40%'],
            'action' => ['name' => 'Ações', 'title' => 'Salvar', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=> '5%'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'mascarapadraorelacionarinsumodatatables_' . time();
    }
}
