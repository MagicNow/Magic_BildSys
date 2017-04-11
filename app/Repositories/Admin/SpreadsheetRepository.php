<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 04/04/2017
 * Time: 11:27
 */

namespace App\Repositories\Admin;

use App\Models\Grupo;
use App\Models\Insumo;
use App\Models\Orcamento;
use App\Models\Planilha;
use App\Models\Servico;
use App\Models\User;
use App\Notifications\PlanilhaProcessamento;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Flash;
use Illuminate\Support\Facades\Redirect;

class SpreadsheetRepository
{
    /**
     *  MÉTODO Spreadsheet:
        Responsável por fazer a primeira leitura da planilha e percorrer o cabeçalho,
         retornando-o para o controller onde será salvo no banco de dados em formato JSON.
     * @param $spreadsheet = Variável responsável por informar a planilha que foi inserido pelo usuário.
     * @param $parametros = Variável responsável por informar os parametros enviado pelo usuário.
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function Spreadsheet($spreadsheet, $parametros){
        try{
            /* Verifica se foi selecionado algum arquivo e salva na tabela *planilhas* */
            if ($spreadsheet['file']) {
                # Pegando nome do arquivo
                $nome = str_slug($spreadsheet['file']->getClientOriginalName());
                # pegando nome e contatenando com extensão e rand
                $nome =  substr($nome,0,strlen($nome)-3).rand(1000, 99999).'.'.$spreadsheet['file']->getClientOriginalExtension();
                # Salvando o arquivo na pasta storage/app/public
                $destinationPath = $spreadsheet['file']->storeAs('public/planilhas', $nome);

                # save table spreadsheet
                $planilha = new Planilha();
                $planilha->user_id = \Auth::id();
                $planilha->arquivo = $destinationPath;
                $planilha->parametros_json = $parametros;
                $planilha->save();


                $cabecalho = [];

                $reader = ReaderFactory::create(Type::CSV);
                $reader->setFieldDelimiter(';');
                $reader->setEndOfLineCharacter("\r");
                $reader->setEncoding('UTF-8');
                $reader->open(str_replace('public','storage/app/',public_path()).$destinationPath);

                $folha = 0;
                foreach ($reader->getSheetIterator() as $sheet) {
                    if ($folha === 0) {
                        $linha = 0;
                        foreach ($sheet->getRowIterator() as $row) {
                            $linha++;
                            if ($linha === 1) {
                                foreach ($row as $index => $valor) {
                                    $cabecalho[str_slug($valor, '_')] = $index;
                                }
                                # Pegando as colunas que foi informado no MODEL orçamento variável = $relation
                                $columns = Orcamento::$relation;
                                # Retornando para o controller o $cabeçalho da planilha e $colunas do banco de dados.
                                return ['cabecalho' => $cabecalho, 'colunas' => $columns];
                            }
                        }
                    }
                    $folha++;
                }
                $reader->close();
            }

            \Flash::error('Escolha um arquivo');
            return back()->with('error', 'Escolha um arquivo!');
        }catch(\Exception $e) {
            \Flash::info('Não foi possivel fazer a leitura do cabeçalho.');
            return Redirect::back();
        }
    }

    /**
     * MÉTODO EXECUTADO POR FILA DE PROCESSAMENTO.
     * MÉTODO SpreadsheetProcess:
     * Responsável por percorrer os campos selecionados linha a linha onde será feito todas as validações de DECIMAL, INTEGER, STRING.
     * Caso ocorrer erro nas validações imediatamente será feito um ROLLBACK.
     * @param $planilha
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     */
    public static function SpreadsheetProcess($planilha)
    {
        $line = 1;
        $reader = ReaderFactory::create(Type::CSV);
        $reader->setFieldDelimiter(';');
        $reader->setEndOfLineCharacter("\r");
        $reader->setEncoding('UTF-8');
        $reader->open(str_replace('public','storage/app/',public_path()).$planilha->arquivo);

        $folha = 0;
        $erro = 0;
        $mensagens_erro = [];
        $final = [];
        \DB::beginTransaction();

        $param = [];
        foreach(json_decode($planilha->parametros_json) as $parametro_key => $parametro_value){
            $param[$parametro_key] = $parametro_value;
        }

        $orcamento_ativos = Orcamento::where('ativo', 1)
            ->where('obra_id', $param['obra_id'])
            ->where('orcamento_tipo_id',$param['orcamento_tipo_id'])
            ->update(['ativo'=>0]);


        foreach ($reader->getSheetIterator() as $sheet) {
            if ($folha === 0) {
                $linha = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    $linha++;
                    if ($linha > 1) {
                        $line++;

                        $colunas = json_decode($planilha->colunas_json);
//                        dd($colunas);
                        foreach ($colunas as $chave => $value) {
                            if($value) {
                                if($row[$chave]) {
                                    switch (Orcamento::$relation[$value]) {
                                        case 'string' :
                                            if (is_string($row[$chave])) {
                                                $final[$value] = $row[$chave];
                                            } else {
                                                if ($row[$chave]) {
                                                    $final[$value] = (string)($row[$chave]);
                                                } else {
                                                    $erro = 1;
                                                    $mensagens_erro[] = 'O campo ' . $value . ' não é do tipo STRING';;
                                                }
                                            }
                                            break;

                                        case 'decimal' :
                                            if (is_float($row[$chave])) {
                                                $final[$value] = str_replace(",",".",$row[$chave]);
                                            } else {
                                                if ($row[$chave]) {
                                                    $final[$value] =  floatval(str_replace(",", ".", str_replace(".", "", $row[$chave])));
                                                } else {
                                                    $erro = 1;
                                                    $mensagens_erro[] = 'O campo ' . $value . ' não é do tipo DECIMAL';
                                                }
                                            }
                                            break;

                                        case 'integer' :
                                            if (is_int($row[$chave])) {
                                                $final[$value] = $row[$chave];
                                            } else {
                                                if ($row[$chave]) {
                                                    $final[$value] = (int)($row[$chave]);
                                                } else {
                                                    $erro = 1;
                                                    $mensagens_erro[] = 'O campo ' . $value . ' não é do tipo INTEGER';
//                                                $mensagens_erro[] = 'LINHA:'.$line.' - O campo '.$value.' não é do tipo INTEGER';
                                                }
                                            }
                                            break;
                                        default:
                                            $final[$value] = $row[$chave];
                                            break;
                                    }
                                }
                            }
                            $parametros = json_decode($planilha->parametros_json);
                            foreach ($parametros as $indice => $valor) {
                                $final[$indice] = $valor ;
                            }

                        }
                        #quebrar codigo insumo explode(separar os pontos)
                        if($final['codigo_insumo']) {
                            $codigo_insumo = explode(".", $final['codigo_insumo']);
                            $codigo_grupo = $codigo_insumo[0];
                            $codigo_subgrupo1 = $codigo_insumo[0] . '.' . $codigo_insumo[1];
                            $codigo_subgrupo2 = $codigo_insumo[0] . '.' . $codigo_insumo[1] . '.' . $codigo_insumo[2];
                            $codigo_subgrupo3 = $codigo_insumo[0] . '.' . $codigo_insumo[1] . '.' . $codigo_insumo[2] . '.' . $codigo_insumo[3];
                            $codigo_servico = $codigo_insumo[0] . '.' . $codigo_insumo[1] . '.' . $codigo_insumo[2] . '.' . $codigo_insumo[3] . '.' . $codigo_insumo[4];
                            $codigo_insumo = $codigo_insumo[5];
                        }

                        # query verificar se existe grupo_id = obra

//                        # verifico esse ($codigo_grupo) ou ($param['obra_id']) ?
                        $grupo = Grupo::where('codigo', $param['obra_id'])->first();
                        if($grupo) {
                            $final['grupo_id'] = $grupo->id;
                        }else{
                            $erro = 1;
                            $mensagens_erro[] = 'grupo - Código: ' . $param['obra_id'] . ' não foi encontrado.';
                        }

                        # query verificar se existe subgrupo1_id
                        $subgrupo1 = Grupo::where('codigo', $codigo_subgrupo1)->first();
                        if($subgrupo1) {
                            $final['subgrupo1_id'] = $subgrupo1->id;
                        }else{
                            $erro = 1;
                            $mensagens_erro[] = 'subgrupo1 - Código: ' . $codigo_subgrupo1 . ' não foi encontrado.';
                        }

                        # query verificar se existe subgrupo2_id
                        $subgrupo2 = Grupo::where('codigo', $codigo_subgrupo2)->first();
                        if($subgrupo2) {
                            $final['subgrupo2_id'] = $subgrupo2->id;
                        }else{
                            $erro = 1;
                            $mensagens_erro[] = 'subgrupo2 - Código: ' . $codigo_subgrupo2 . ' não foi encontrado.';
                        }

                        # query verificar se existe subgrupo3_id
                        $subgrupo3 = Grupo::where('codigo', $codigo_subgrupo3)->first();
                        if($subgrupo3) {
                            $final['subgrupo3_id'] = $subgrupo3->id;
                        }else{
                            $erro = 1;
                            $mensagens_erro[] = 'subgrupo3 - Código: ' . $codigo_subgrupo3 . ' não foi encontrado.';
                        }

                        # query verificar se existe servico_id
                        $servico = Servico::where('codigo', $codigo_servico)->first();
                        if($servico) {
                            $final['servico_id'] = $servico->id;
                        }else{
                            $erro = 1;
                            $mensagens_erro[] = 'Serviço - Código: ' . $codigo_servico . ' não foi encontrado.';
                        }

                        # query verificar se existe insumo_id
                        $insumo = Insumo::where('codigo', $codigo_insumo)->first();
                        if($servico) {
                            $final['insumo_id'] = $insumo->id;
                        }else{
                            $erro = 1;
                            $mensagens_erro[] = 'Insumo - Código: ' . $codigo_insumo . ' não foi encontrado.';
                        }

//                        dd($final);
                        # save data table budget
                        if($erro == 0) {
                            $orcamento = Orcamento::create($final);
                        }else{
                            // estourar loop
                            $erro = 1;
                        }
                    }
                }
            }
            $folha++;
        }

        # finish transaction
        if($erro == 0) {
            \DB::commit();
        }else{
            \DB::rollBack();
        }
        # close reader
        $reader->close();

        $user = User::find($final['user_id']);
        if($user){
            $user->notify(new PlanilhaProcessamento(['success'=>!count($mensagens_erro),'error'=>array_unique($mensagens_erro)]));
        }
    }
}