<?php

namespace App\Http\Controllers;

use App\DataTables\QcDataTable;
use App\DataTables\QcAnexosDataTable;
use Illuminate\Http\Request;

use Laracasts\Flash\Flash;
use App\Repositories\QcRepository;
use App\Repositories\QcAnexoRepository;
use App\Repositories\CodeRepository;
use App\Http\Requests\CreateQcRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Obra;
use App\Models\Carteira;
use App\Models\Tipologia;

class QcController extends AppBaseController
{
	/** @var  QcRepository */
	private $qcRepository;

	public function __construct(QcRepository $qcRepo, QcAnexoRepository $qcAnexoRepo)
	{
		$this->qcRepository = $qcRepo;
		$this->qcAnexoRepository = $qcAnexoRepo;
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
		$tipologias = Tipologia::pluck('nome','id')->toArray();

		return view('qc.create', compact('obras', 'carteiras', 'tipologias'));
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

		if($request->anexo_arquivo){
			foreach($request->anexo_arquivo as $key => $file) {
				$destinationPath = CodeRepository::saveFile($file, 'qc/' . $qc->id);

				$attach = $this->qcAnexoRepository->create([
					'arquivo' => $destinationPath,
					'tipo' => $request->anexo_tipo[$key],
					'descricao' => $request->anexo_descricao[$key],
				]);

				$qc->anexos()->save($attach);
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

		$attachments = [];

		if (isset($qc->anexos) && !empty($qc->anexos)) {
			foreach ($qc->anexos as $attachment) {
				if (!isset($attachments[$attachment->tipo])) {
					$attachments[$attachment->tipo] = [];
				}

				$attachments[$attachment->tipo][] = $attachment;
			}
		}

		if (empty($qc)) {
			Flash::error('Qc '.trans('common.not-found'));

			return redirect(route('qc.index'));
		}

		return view('qc.show', compact('qc', 'attachments'));
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
		$tipologias = Tipologia::pluck('nome','id')->toArray();

		if (empty($qc)) {
			Flash::error('Qc '.trans('common.not-found'));

			return redirect(route('qc.index'));
		}

		return view('qc.edit', compact('qc', 'obras', 'carteiras', 'tipologias'));
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

		if($request->anexo_arquivo){
			foreach($request->anexo_arquivo as $key => $file) {
				$destinationPath = CodeRepository::saveFile($file, 'qc/' . $qc->id);

				$attach = $this->qcAnexoRepository->create([
					'arquivo' => $destinationPath,
					'tipo' => $request->anexo_tipo[$key],
					'descricao' => $request->anexo_descricao[$key],
				]);

				$qc->anexos()->save($attach);
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
	 * Approve and disapprove Q.C.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function aprovar ($id) {
		$qc = $this->qcRepository->findWithoutFail($id);

		if (empty($qc)) {
			Flash::error('Qc '.trans('common.not-found'));

			return redirect(route('qc.index'));
		}

		return view('qc_aprovar.edit', compact('qc'));
	}

	public function aprovarUpdate () {
		
	}
}
