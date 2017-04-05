<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 04/04/2017
 * Time: 11:47
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AppBaseController;
use App\Models\Modulo;
use App\Models\Planilha;
use App\Models\TipoOrcamento;
use App\Repositories\Admin\SpreadsheetRepository;
use Symfony\Component\HttpFoundation\Request;

class ImportController extends AppBaseController
{
    public function index(){
        $modulos = TipoOrcamento::pluck('nome','id')->toArray();
        return view('admin.import.index', compact('modulos'));
    }

    public function import(Request $request){

        $retorno = SpreadsheetRepository::Spreadsheet($request->file, $request->modulo_id);
        dd($retorno);

        foreach ($retorno['colunas'] as $coluna => $type ) {
            $colunasbd[$coluna] = $coluna . ' - ' . $type;
        }
        return view('admin.import.checkIn', compact('retorno','colunasbd'));

    }

    public function save(Request $request){
        $input = $request->except('_token');
        $json = json_encode($input);

        $planilha = Planilha::where('user_id', \Auth::id())->first();
        if($planilha) {
            $planilha->json = $json;
            $planilha->update();
        }
        SpreadsheetRepository::SpreadsheetProcess($planilha);

    }
}