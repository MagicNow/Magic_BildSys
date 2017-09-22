<?php

namespace App\Http\Controllers;

use App\DataTables\QcDataTable;
use App\DataTables\QcAnexosDataTable;
use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use App\Repositories\QcRepository;
use App\Repositories\CodeRepository;
use App\Http\Requests\CreateQcRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Obra;
use App\Models\Carteira;
use App\Models\Topologia;

class QcController extends AppBaseController
{
	/** @var  QcRepository */
	private $qcRepository;

	public function __construct(QcRepository $qcRepo)
	{
		$this->qcRepository = $qcRepo;
	}

	/**
	 * Display a listing of the Carteiras Sla.
	 *
	 * @param QcDataTable $qcDataTable
	 * @return Response
	 */
	public function index(QcDataTable $qcDataTable) {
		return $qcDataTable->render(
			'qc.index'
		);
	}

	/**
	 * Show the form for creating a new Qc.
	 *
	 * @return Response
	 */
	public function create()
	{
		$obras = Obra::pluck('nome','id')->toArray();
		$carteiras = Carteira::pluck('nome','id')->toArray();
		$topologias = Topologia::pluck('nome','id')->toArray();

		return view('qc.create', compact('obras', 'carteiras', 'topologias'));
	}

	/**
	 * Store a newly created Qc in storage.
	 *
	 * @param CreateQcRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateQcRequest $request)
	{
		$input = $request->except('file');
		$qc = $this->qcRepository->create($input);

		if($request->file){
			foreach($request->file as $file) {
				$destinationPath = CodeRepository::saveFile($file, 'qc/' . $qc->id);
			}
		}

		Flash::success('QC '.trans('common.saved').' '.trans('common.successfully').'.');

		return redirect(route('qc.index'));
	}

	/**
	 * Display the specified Qc.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$qc = $this->qcRepository->findWithoutFail($id);

		if (empty($qc)) {
			Flash::error('Qc '.trans('common.not-found'));

			return redirect(route('qc.index'));
		}

		return view('qc.show')->with('qc', $qc);
	}

	/**
	 * Show the form for editing the specified Qc.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		$qc = $this->qcRepository->findWithoutFail($id);
		$obras = Obra::pluck('nome','id')->toArray();
		$carteiras = Carteira::pluck('nome','id')->toArray();
		$topologias = Topologia::pluck('nome','id')->toArray();

		if (empty($qc)) {
			Flash::error('Qc '.trans('common.not-found'));

			return redirect(route('qc.index'));
		}

		return view('qc.edit', compact('qc', 'obras', 'carteiras', 'topologias'));
	}

	/**
	 * Update the specified Grupo in storage.
	 *
	 * @param  int              $id
	 * @param UpdateGrupoRequest $request
	 *
	 * @return Response
	 */
	public function update($id, CreateQcRequest $request)
	{
		$input = $request->except('file');
		$qc = $this->qcRepository->findWithoutFail($id);

		if (empty($qc)) {
			Flash::error('Qc '.trans('common.not-found'));

			return redirect(route('qc.index'));
		}

		$qc = $this->qcRepository->update($input, $id);

		if($request->file){
			foreach($request->file as $file) {
				$destinationPath = CodeRepository::saveFile($file, 'qc/' . $qc->id);
			}
		}

		Flash::success('Grupo '.trans('common.updated').' '.trans('common.successfully').'.');

		return redirect(route('qc.index'));
	}

	/**
	 * Remove the specified Qc from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$qc = $this->qcRepository->findWithoutFail($id);

		if (empty($qc)) {
			Flash::error('Qc '.trans('common.not-found'));

			return redirect(route('qc.index'));
		}

		$this->qcRepository->delete($id);

		Flash::success('Qc '.trans('common.deleted').' '.trans('common.successfully').'.');

		return redirect(route('qc.index'));
	}

	/**
	 * Display the specified attachments to Qc.
	 *
	 * @param  int $id
	 * @param QcAnexosDataTable $qcAnexosDataTable
	 *
	 * @return Response
	 */
	public function anexos(QcAnexosDataTable $qcAnexosDataTable, $id)
	{
		$qc = $this->qcRepository->findWithoutFail($id);

		if (empty($qc)) {
			Flash::error('Qc '.trans('common.not-found'));

			return redirect(route('qc.index'));
		}

		return $qcAnexosDataTable
			->with(['id' => $id])
			->render(
				'qc_anexos.index'
			);
	}
}
