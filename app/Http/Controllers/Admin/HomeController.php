<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notificacao;
use App\Repositories\Admin\ValidationRepository;
use App\Repositories\ImportacaoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

class HomeController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.home');
    }

//    public function validaCnpj(Request $request){
//        $validator = ValidationRepository::validaCnpj($request->numero,$request->cpf);
//
//        $validator->validate();
//
//        // verifica se já não existe o cnpj com outro fornecedor
//        $documentoUnico = ValidationRepository::CnpjUnico($request->numero);
//        if($documentoUnico){
//            return response()->json(['success'=>false,'erro'=>'CNPJ já cadastrado na base!'],422);
//        }else{
//            $retorno = ImportacaoRepository::fornecedores($request->numero);
//            if($retorno) {
//                return response()->json(['success' => true, 'msg'=>'Fornecedor já existente no banco MEGA e importado automaticamente', 'importado'=>1, 'fornecedor'=>$retorno]);
//            }
//        }
//        return response()->json(['success'=>true, 'importado'=> 0]);
//    }
}
