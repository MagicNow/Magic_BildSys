<?php

namespace App\DataTables;

use App\Models\User;
use App\Repositories\CodeRepository;
use Form;
use Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class UserDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('action', 'admin.manage.users.datatables_actions')
            ->editColumn('active', '{!! $active?\'<i class="fa fa-check text-success"></i>\':\'<i class="fa fa-times text-danger"></i>\' !!}')
            ->editColumn('admin', '{!! $admin?\'<i class="fa fa-check text-success"></i>\':\'<i class="fa fa-times text-danger"></i>\' !!}')
            ->editColumn('created_at', function($obj){
                return $obj->created_at->format('d/m/Y');
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(users.created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
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

        $users = User::query();

        CodeRepository::filter($users, Request::all());

        return $this->applyScopes($users);
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
                'order' => [1,'asc'],
                'responsive'=> 'true',
                "initComplete" => 'function () {
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
                    "url"=> asset("vendor/datatables/Portuguese-Brasil.json")
                ],
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Exportar',
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
            'id' => ['name' => 'id', 'data' => 'id', 'width'=>'6%'],
            'nome' => ['name' => 'name', 'data' => 'name'],
            'e-mail' => ['name' => 'email', 'data' => 'email'],
            'ativo' => ['name' => 'active', 'data' => 'active', 'width'=>'4%'],
            'admin' => ['name' => 'admin', 'data' => 'admin', 'width'=>'4%'],
            'criadoEm' => ['name' => 'created_at', 'data' => 'created_at', 'width'=>'8%'],
            'action' => ['title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%', 'class' => 'all']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'users';
    }
}
