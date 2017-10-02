<?php

namespace App\Http\Controllers;

use App\DataTables\QcSuprimentosDataTable;
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
use App\Models\User;
use App\Models\Fornecedor;

class QcSuprimentosController extends AppBaseController
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
     * @param QcSuprimentosDataTable $qcSuprimentosDataTable
     * @return Response
     */
    public function index(QcSuprimentosDataTable $qcSuprimentosDataTable) {
        return $qcSuprimentosDataTable->render(
            'qc_suprimentos.index'
        );
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
        $fornecedores = Fornecedor::pluck('nome','id')->toArray();
        $comprador = User::pluck('name','id')->toArray();

        if (empty($qc)) {
            Flash::error('Qc '.trans('common.not-found'));

            return redirect(route('qc_suprimentos.index'));
        }

        return view('qc_suprimentos.edit', compact('qc', 'obras', 'carteiras', 'tipologias', 'fornecedores', 'comprador'));
    }

    /**
     * Update the specified Q.C. in storage.
     *
     * @param  int              $id
     * @param UpdateQcRequest $request
     *
     * @return Response
     */
    public function update($id, CreateQcRequest $request)
    {
        $input = $request->except('file');
        $qc = $this->qcRepository->findWithoutFail($id);

        $input['valor_fechamento'] = $request->valor_fechamento ? preg_replace('/[^0-9\.]/', '', $request->valor_fechamento) : NULL;
        $input['data_fechamento'] = $request->status == 'Fechado' ? date('Y-m-d H:i:s') : NULL;

        if (empty($qc)) {
            Flash::error('Qc '.trans('common.not-found'));

            return redirect(route('listaQc.index'));
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

        Flash::success('Q.C. '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('listaQc.index'));
    }
}
