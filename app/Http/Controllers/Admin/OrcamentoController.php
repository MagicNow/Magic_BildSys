<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 04/04/2017
 * Time: 11:47
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AppBaseController;
use App\Jobs\PlanilhaProcessa;
use App\Models\Obra;
use App\Models\Planilha;
use App\Models\TipoOrcamento;
use App\Repositories\Admin\SpreadsheetRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Flash;

class OrcamentoController extends AppBaseController
{
    /**
     * $obras = Buscando chave e valor para fazer o combobox da view
     * $orcamento_tipos = Buscando chave e valor para fazer o combobox da view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $obras = Obra::pluck('nome','id')->toArray();
        $orcamento_tipos = TipoOrcamento::pluck('nome','id')->toArray();
        return view('admin.orcamento.index', compact('orcamento_tipos','obras'));
    }

    /**
     * $request = Recebendo campos na view
     * $file = Pegando campos request exceto os que está dentro da exceção
     * $input = Pegando campos request exceto os campos que está dentro da exceção
     * $input['user_id'] = pegando usuário logado
     * $parametros = pegando $input e tranformando em json
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function import(Request $request)
    {
        $tipo = 'orcamento';
        $file = $request->except('obra_id','modulo_id','orcamento_tipo_id');
        $input = $request->except('_token','file');
        $obra_id = null;
        $modulo_id = null;
        $orcamento_tipo_id = null;

        # Validando campos obrigatórios como chave estrangeiras
        if($input['obra_id'] != "") {
            $obra_id = array_key_exists('obra_id', $input);
        }
        if($input['modulo_id'] != "") {
            $modulo_id = array_key_exists('modulo_id', $input);
        }
        if($input['orcamento_tipo_id'] != "") {
            $orcamento_tipo_id = array_key_exists('orcamento_tipo_id', $input);
        }
        if(!$obra_id || !$modulo_id || !$orcamento_tipo_id){
            Flash::error('Os campos: obra, modulo e tipo orçamento são obrigátorios.');
            return back();
        }


        $input['user_id'] = Auth::id();
        $parametros = json_encode($input);
        $colunasbd = [];

        # Enviando $file e $parametros para método de leitura da planilha.
        $retorno = SpreadsheetRepository::Spreadsheet($file, $parametros, $tipo);

        /* Percorrendo campos retornados e enviando para a view onde o
            usuário escolhe as colunas que vão ser importadas e tipos.
        */
        foreach ($retorno['colunas'] as $coluna => $type ) {
            $colunasbd[$coluna] = $coluna . ' - ' . $type;
        }

        # Colocando variaveis na sessão para fazer validações de campos obrigatórios.
        \Session::put('retorno', $retorno);
        \Session::put('colunasbd', $colunasbd);

        return redirect('/admin/orcamento/importar/selecionaCampos');
    }

    /**
     * Método para tranformar a requisição de POST para GET onde vamos fazer a validações dos campos obrigatórios
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function selecionaCampos(Request $request){

        $retorno = $request->session()->get('retorno');
        $colunasbd = $request->session()->get('colunasbd');
        return view('admin.orcamento.checkIn', compact('retorno','colunasbd'));
    }

    /*
     * $request = Pegando os campos selecionado de colunas a ser importadas e tipos das colunas.
     * Método responsável por enviar os dados para o método da fila.
     */
    public function save(Request $request){
        $input = $request->except('_token');
        $json = json_encode(array_filter($input));

        # Validando campos obrigatórios como chave estrangeiras
        $codigo_insumo = in_array('codigo_insumo', $input);
        $unidade_sigla = in_array('unidade_sigla', $input);
        if(!$codigo_insumo && !$unidade_sigla){
            Flash::error('Os campos: codigo_insumo e unidade_sigla são obrigátorios.');
            return back();
        }

        # Pegando todas as planilhas por ordem decrescente
        $planilha = Planilha::orderBy('id','desc')->get();
        # consulta que trás somente a ultima planilha importada pelo usuário
        $planilha = $planilha->where('user_id', \Auth::id())->first();
        # Após encontrar a planilha, será feito um update adicionando em array os campos escolhido pelo usuário.
        if($planilha) {
            $planilha->colunas_json = $json;
            $planilha->update();
        }

        # Comentário de processamento de fila iniciada
        \Log::info("Ciclo de solicitações com filas iniciada");
        dispatch(new PlanilhaProcessa($planilha));
        # Comentário de processamento de fila finalizada
        \Log::info("Ciclo de solicitações com filas finalizada");

        # Mensagem que será exibida para o usuário avisando que a importação foi adicionada na fila e será processada.
        Flash::warning('Importação incluida na FILA. Ao concluir o processamento enviaremos um ALERTA!');
        return redirect('admin/orcamento');
    }
}