<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 04/04/2017
 * Time: 11:47
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AppBaseController;
use App\Models\Obra;
use App\Models\Orcamento;
use App\Models\Planilha;
use App\Models\TipoOrcamento;
use App\Repositories\Admin\SpreadsheetRepository;
use Symfony\Component\HttpFoundation\Request;

class ImportController extends AppBaseController
{
    public function index(){
        $obras = Obra::pluck('nome','id')->toArray();
        $orcamento_tipos = TipoOrcamento::pluck('nome','id')->toArray();
        return view('admin.import.index', compact('orcamento_tipos','obras'));
    }

    public function import(Request $request)
    {
        $file = $request->except('obra_id','modulo_id','orcamento_tipo_id');
        $input = $request->except('_token','file');

        $parametros = json_encode($input);

        $retorno = SpreadsheetRepository::Spreadsheet($file, $parametros);

        foreach ($retorno['colunas'] as $coluna => $type ) {
            $colunasbd[$coluna] = $coluna . ' - ' . $type;
        }
        return view('admin.import.checkIn', compact('retorno','colunasbd'));

    }

    public function save(Request $request){
        $input = $request->except('_token');

        # verifica na tabela final os campos obrigatorios e verificar
//        $rules = Orcamento::$rules;
//        foreach ($input as $item) {
//        }
//        unset($input[0]);
//        dd($rules,$input);

        $json = json_encode($input);

        $planilha = Planilha::orderBy('id','desc')->get();
        $planilha = $planilha->where('user_id', \Auth::id())->first();
        if($planilha) {
            $planilha->colunas_json = $json;
            $planilha->update();
        }
        $retorno = SpreadsheetRepository::SpreadsheetProcess($planilha);

        if($retorno){
            # envia pra view
            return view('admin.import.index', compact('retorno'));
        }

    }
}