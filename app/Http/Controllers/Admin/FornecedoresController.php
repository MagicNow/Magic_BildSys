<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\FornecedoresDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateFornecedoresRequest;
use App\Http\Requests\Admin\UpdateFornecedoresRequest;
use App\Repositories\Admin\FornecedoresRepository;
use App\Repositories\Admin\ValidationRepository;
use App\Repositories\ImportacaoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;
use Correios;

class FornecedoresController extends AppBaseController
{
    /** @var  FornecedoresRepository */
    private $fornecedoresRepository;

    public function __construct(FornecedoresRepository $fornecedoresRepo)
    {
        $this->fornecedoresRepository = $fornecedoresRepo;
    }

    /**
     * Display a listing of the Fornecedores.
     *
     * @param FornecedoresDataTable $fornecedoresDataTable
     * @return Response
     */
    public function index(FornecedoresDataTable $fornecedoresDataTable)
    {
        return $fornecedoresDataTable->render('admin.fornecedores.index');
    }

    /**
     * Show the form for creating a new Fornecedores.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.fornecedores.create');
    }

    /**
     * Store a newly created Fornecedores in storage.
     *
     * @param CreateFornecedoresRequest $request
     *
     * @return Response
     */
    public function store(CreateFornecedoresRequest $request)
    {
        $input = $request->all();

        $fornecedores = $this->fornecedoresRepository->create($input);

        Flash::success('Fornecedores '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.fornecedores.index'));
    }

    /**
     * Display the specified Fornecedores.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $fornecedores = $this->fornecedoresRepository->findWithoutFail($id);

        if (empty($fornecedores)) {
            Flash::error('Fornecedores '.trans('common.not-found'));

            return redirect(route('admin.fornecedores.index'));
        }

        return view('admin.fornecedores.show')->with('fornecedores', $fornecedores);
    }

    /**
     * Show the form for editing the specified Fornecedores.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $fornecedores = $this->fornecedoresRepository->findWithoutFail($id);

        if (empty($fornecedores)) {
            Flash::error('Fornecedores '.trans('common.not-found'));

            return redirect(route('admin.fornecedores.index'));
        }

        return view('admin.fornecedores.edit')->with('fornecedores', $fornecedores);
    }

    /**
     * Update the specified Fornecedores in storage.
     *
     * @param  int              $id
     * @param UpdateFornecedoresRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFornecedoresRequest $request)
    {
        $fornecedores = $this->fornecedoresRepository->findWithoutFail($id);

        if (empty($fornecedores)) {
            Flash::error('Fornecedores '.trans('common.not-found'));

            return redirect(route('admin.fornecedores.index'));
        }

        $fornecedores = $this->fornecedoresRepository->update($request->all(), $id);

        Flash::success('Fornecedores '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.fornecedores.index'));
    }

    /**
     * Remove the specified Fornecedores from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $fornecedores = $this->fornecedoresRepository->findWithoutFail($id);

        if (empty($fornecedores)) {
            Flash::error('Fornecedores '.trans('common.not-found'));

            return redirect(route('admin.fornecedores.index'));
        }

        $this->fornecedoresRepository->delete($id);

        Flash::success('Fornecedores '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.fornecedores.index'));
    }

    public function buscaPorCep($cep)
    {
        return Correios::cep($cep);
    }

    public function validaCnpj(Request $request){
        $validator = ValidationRepository::validaCnpj($request->numero,$request->cpf);

        $validator->validate();

        // verifica se já não existe o cnpj com outro fornecedor
        $documentoUnico = ValidationRepository::CnpjUnico($request->numero);
        if($documentoUnico){
            return response()->json(['success'=>false,'erro'=>'CNPJ já cadastrado na base!'],422);
        }else{
            $retorno = ImportacaoRepository::fornecedores($request->numero);
            if($retorno) {
                return response()->json(['success' => true, 'msg'=>'Fornecedor já existente no banco MEGA e importado automaticamente', 'importado'=>1, 'fornecedor'=>$retorno]);
            }
        }
        return response()->json(['success'=>true]);
    }
}
