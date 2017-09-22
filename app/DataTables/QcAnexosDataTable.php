<?php

namespace App\DataTables;

use App\Models\Qc;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Facades\DB;
use File;
use Request;
use Carbon\Carbon;

class QcAnexosDataTable extends DataTable
{

	/**
	 * Display ajax response.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function ajax()
	{
		$qc = Qc::findOrFail($this->attributes['id']);

		if (empty($qc)) return;

		$collect = [];
		$path = storage_path('app/public/qc/' . $qc->id);
		$files = File::allFiles($path);
		foreach ($files as $file)
		{
			$filename = basename($file);
			$collect[] = [
				'id' => $qc->id,
				'nome' => $filename,
				'mime' => mime_content_type($path . '/' . $filename),
				'action' => 'qc_anexos.datatables_actions'
			];
		}

		$collection = collect($collect);

		return $this->datatables
			->collection($collection)
			->editColumn('action', 'qc_anexos.datatables_actions')
			->make(true);
	}

	/**
	 * Get the query object to be processed by dataTables.
	 */
	public function query()
	{
		
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\Datatables\Html\Builder
	 */
	public function html()
	{
		return $this
			->builder()
			->ajax('')
			->columns($this->getColumns())
			->parameters([
				'responsive'=> 'true',
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
				'dom' => 'Blfrtip',
				'scrollX' => false,
				'language'=> [
					"url"=> "/vendor/datatables/Portuguese-Brasil.json"
				],
				// Ordena para que inicialmente carregue os mais novos
				'order' => [
					1,
					'desc'
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
			'nome' => ['name' => 'nome', 'data' => 'nome', 'title' => 'Nome'],
			'mimetype' => ['name' => 'mimetype', 'data' => 'mime', 'title' => 'Tipo'],
			'action' => ['name' => 'Ações', 'title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'15%', 'class' => 'all']
		];
	}
}
