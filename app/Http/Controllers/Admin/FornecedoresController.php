<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\FornecedoresDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateFornecedoresRequest;
use App\Http\Requests\Admin\UpdateFornecedoresRequest;
use App\Models\CatalogoContrato;
use App\Models\Cnae;
use App\Models\Contrato;
use App\Models\Fornecedor;
use App\Models\FornecedorServico;
use App\Models\QcFornecedor;
use App\Models\Servico;
use App\Models\User;
use App\Repositories\Admin\FornecedoresRepository;
use App\Repositories\Admin\ValidationRepository;
use App\Repositories\ImportacaoRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;
use Correios;
use Illuminate\Support\Facades\DB;

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
        $view = 'admin.fornecedores.create';
        if(request('modal')=='1'){
            $view = 'admin.fornecedores.create-modal';
        }
        return view($view);
    }

    /**
     * Store a newly created Fornecedor in storage.
     *
     * @param CreateFornecedoresRequest $request
     *
     * @return Response
     */
    public function store(CreateFornecedoresRequest $request)
    {
        $usuario_existente_deletado = User::Where('email', '=', $request->email)
            ->withTrashed()
            ->whereNotNull('deleted_at')
            ->first();

        $fornecedor_existente_deletado = Fornecedor::Where('email', '=', $request->email)
            ->withTrashed()
            ->whereNotNull('deleted_at')
            ->first();

//        $usuario_existente = User::Where('email', '=', $request->email)
//            ->first();
//
//        $fornecedor_existente = Fornecedor::Where('email', '=', $request->email)
//            ->first();

        if (isset($usuario_existente_deletado)) {
            $usuario_existente_deletado->forceDelete();
        }
        if (isset($fornecedor_existente_deletado)) {
            $fornecedor_existente_deletado->forceDelete();
        }


        $this->validate($request, [
            'email' => 'unique:fornecedores|unique:users'
        ]);

        $input = $request->all();

        $fornecedor = $this->fornecedoresRepository->create($input);

        if(!$request->ajax()) {
            Flash::success('Fornecedor '.trans('common.saved').' '.trans('common.successfully').'.');

            return redirect(route('admin.fornecedores.index'));
        }

        return $fornecedor;
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

        $servicos = FornecedorServico::select([
            'fornecedor_servicos.codigo_fornecedor_id',
            'fornecedor_servicos.codigo_servico_id',
            'servicos_cnae.nome'
        ])
            ->join('servicos_cnae','servicos_cnae.id','=','fornecedor_servicos.codigo_servico_id')
            ->where('fornecedor_servicos.codigo_fornecedor_id', $id)
            ->get();

        if (empty($fornecedores)) {
            Flash::error('Fornecedor '.trans('common.not-found'));

            return redirect(route('admin.fornecedores.index'));
        }

        return view('admin.fornecedores.show', compact('servicos'))->with('fornecedores', $fornecedores);
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
        $servicos = Cnae::pluck('nome', 'id')->toArray();
        $servicos_fornecedor = FornecedorServico::join('servicos_cnae','servicos_cnae.id','=','fornecedor_servicos.codigo_servico_id')
            ->where('fornecedor_servicos.codigo_fornecedor_id', $id)
            ->pluck('servicos_cnae.id')->all();

        $fornecedores = $this->fornecedoresRepository->findWithoutFail($id);

        if (empty($fornecedores)) {
            Flash::error('Fornecedor '.trans('common.not-found'));

            return redirect(route('admin.fornecedores.index'));
        }

        return view('admin.fornecedores.edit', compact('servicos_fornecedor', 'servicos'))->with('fornecedores', $fornecedores);
    }

    /**
     * Update the specified Fornecedor in storage.
     *
     * @param  int              $id
     * @param UpdateFornecedoresRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFornecedoresRequest $request)
    {
        $usuario_existente_deletado = User::Where('email', '=', $request->email)
            ->withTrashed()
            ->whereNotNull('deleted_at')
            ->first();

        $fornecedor_existente_deletado = Fornecedor::Where('email', '=', $request->email)
            ->withTrashed()
            ->whereNotNull('deleted_at')
            ->first();

//        $fornecedor_user_id = Fornecedor::find($id)->user_id;
//        $usuario_existente = User::Where('email', '=', $request->email)
//            ->where('id', '!=', $fornecedor_user_id)
//            ->first();

        if (isset($usuario_existente_deletado)) {
            $usuario_existente_deletado->forceDelete();
        }

        if (isset($fornecedor_existente_deletado)) {
            $fornecedor_existente_deletado->forceDelete();
        }

//        $this->validate($request, [
//            'email' => 'unique:fornecedores|unique:users'
//        ]);

//        if (isset($usuario_existente)) {
//            Flash::error('O campo email já esta sendo utilizado em outro cadastro');
//            return redirect('/fornecedores/'.$id.'/edit')->withInput($request->except('password', 'password_confirmation'));
//        }

        $fornecedores = $this->fornecedoresRepository->findWithoutFail($id);

        if (empty($fornecedores)) {
            Flash::error('Fornecedor '.trans('common.not-found'));

            return redirect(route('admin.fornecedores.index'));
        }



        $fornecedores = $this->fornecedoresRepository->update($request->all(), $id);

        Flash::success('Fornecedor '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.fornecedores.index'));
    }

    /**
     * Remove the specified Fornecedor from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $fornecedores = $this->fornecedoresRepository->findWithoutFail($id);

        if (empty($fornecedores)) {
            Flash::error('Fornecedor '.trans('common.not-found'));

            return redirect(route('admin.fornecedores.index'));
        }

        # Verificando se o fornecedor tem alguma proposta, caso ele tiver proposta não pode excluir o fornecedor.
        $qc_fornecedor = QcFornecedor::where('fornecedor_id', $id)->first();
        $catalogo_contrato = CatalogoContrato::where('fornecedor_id', $id)->first();
        $contrato = Contrato::where('fornecedor_id', $id)->first();

        #condição
        if(!$qc_fornecedor && !$catalogo_contrato && !$contrato) {
            DB::transaction(function() use ($id, $fornecedores) {
                $this->fornecedoresRepository->delete($id);
                if ($fornecedores->user) {
                    $fornecedores->user->delete();
                }
            });
        }else{
            Flash::info('O fornecedor não pode ser deletado pois está relacionado em alguma PROPOSTA!');
            return redirect(route('admin.fornecedores.index'));
        }

        Flash::success('Fornecedor '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.fornecedores.index'));
    }

    public function buscaPorCep($cep)
    {
        return Correios::cep($cep);
    }

    public function validaCnpj(Request $request){
        $validator = ValidationRepository::validaCnpj($request->numero,$request->cnpj);

        $validator->validate();

        // verifica se já não existe o cnpj com outro fornecedor
        $documentoUnico = ValidationRepository::CnpjUnico($request->numero);

        if($documentoUnico){
            return response()->json(['success' => false, 'erro' => 'CNPJ já cadastrado na base!'],422);
        }else{
            $retorno = ImportacaoRepository::fornecedores($request->numero);
            if($retorno) {
                return response()->json(['success' => true, 'msg'=>'Fornecedor encontrado no MEGA e inserido no SYS', 'importado'=>1, 'fornecedor'=>$retorno]);
            }
        }
        return response()->json(['success'=>true]);
    }

    public function buscaTemporarios(Request $request){
        $fornecedores = Fornecedor::select([
            'id',
            DB::raw("CONCAT(nome, ' - ', cnpj) as nome")
        ])
            ->whereNull('codigo_mega')
            ->where('nome','like', '%'.$request->q.'%')->paginate();
        return $fornecedores;
    }
}
