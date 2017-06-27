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
use App\Models\InsumoServico;
use App\Models\Orcamento;
use App\Models\Planejamento;
use App\Models\Planilha;
use App\Models\Servico;
use App\Models\TemplatePlanilha;
use App\Models\TipoOrcamento;
use App\Models\User;
use App\Notifications\PlanilhaProcessamento;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Carbon\Carbon;
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
    public static function Spreadsheet($spreadsheet, $parametros, $tipo){
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
                $planilha->modulo = $tipo;
                $planilha->save();


                $cabecalho = [];

                if($tipo == 'orcamento') {
                    if(strtolower($spreadsheet['file']->getClientOriginalExtension())=='csv'){
                        $reader = ReaderFactory::create(Type::CSV);
                        $reader->setFieldDelimiter(';');
                        $reader->setEndOfLineCharacter("\r");
                        $reader->setEncoding('UTF-8');
                    }
//                    elseif(strtolower($spreadsheet['file']->getClientOriginalExtension())=='xlsx'){
//                        $reader = ReaderFactory::create(Type::XLSX);
//                    }
//                    elseif(strtolower($spreadsheet['file']->getClientOriginalExtension())=='ods'){
//                        $reader = ReaderFactory::create(Type::ODS);
//                    }

                } elseif ($tipo == 'planejamento'){
                    $reader = ReaderFactory::create(Type::XLSX);
                }
                $reader->open(str_replace('public','storage/app/',public_path()).$destinationPath);

                $folha = 0;
                foreach ($reader->getSheetIterator() as $sheet) {
                    if ($folha === 0) {
                        $linha = 0;
                        foreach ($sheet->getRowIterator() as $row) {
                            $linha++;
                            if ($linha === 1) {
                                foreach ($row as $index => $valor) {
                                    $cabecalho[str_slug(utf8_encode($valor), '_')] = $index;
                                }

                                if($tipo == 'orcamento') {
                                    # Pegando as colunas que foi informado no MODEL orçamento variável = $relation
                                    $columns = Orcamento::$relation;



                                    # Retornando para o controller o $cabeçalho da planilha e $colunas do banco de dados.
                                    return ['cabecalho' => $cabecalho, 'colunas' => $columns, 'planilha_id'=> $planilha->id];
                                }elseif($tipo == 'planejamento') {
                                    # Pegando as colunas que foi informado no MODEL orçamento variável = $relation
                                    $columns = Planejamento::$relation;
                                    # Retornando para o controller o $cabeçalho da planilha e $colunas do banco de dados.
                                    return ['cabecalho' => $cabecalho, 'colunas' => $columns, 'planilha_id'=> $planilha->id];
                                }
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
        if($planilha){
            if ($planilha->modulo == 'orcamento'){
                SpreadsheetRepository::orcamento($planilha);
            } elseif ($planilha->modulo == 'planejamento'){
                SpreadsheetRepository::planejamento($planilha);
            }
        }

    }

    public static function orcamento($planilha){
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

                $line = 1;
                $linha = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    $linha++;
                    $final = [];
                    if ($linha > 1) {
                        $line++;
                        $colunas = json_decode($planilha->colunas_json);
//                        dd($colunas);
                        foreach ($colunas as $chave => $value) {
                            if($value) {
                                if(isset($row[$chave]) ) {
                                    switch (Orcamento::$relation[$value]) {
                                        case 'string' :
                                            if (is_string($row[$chave])) {
//                                                dd([$value,$linha,$row,trim(utf8_encode($row[$chave]))]);
                                                $final[$value] = trim(utf8_encode($row[$chave]));
                                            } else {
                                                if ($row[$chave]) {
                                                    $final[$value] = (string)($row[$chave]);
                                                } else {
                                                    $final[$value] = null;
//                                                    $erro = 1;
//                                                    $mensagens_erro[] = 'O campo ' . $value . ' não é do tipo STRING';;
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
                                                    $final[$value] = null;
//                                                    $erro = 1;
//                                                    $mensagens_erro[] = 'O campo ' . $value . ' na linha ' . $line .' não é do tipo DECIMAL';
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
                                                    $final[$value] = null;
//                                                    $erro = 1;
//                                                    $mensagens_erro[] = 'O campo ' . $value . ' não é do tipo INTEGER';
//                                                $mensagens_erro[] = 'LINHA:'.$line.' - O campo '.$value.' não é do tipo INTEGER';
                                                }
                                            }
                                            break;
                                        default:
                                            if($row[$chave]) {
                                                $final[$value] = $row[$chave];
                                            }else{
                                                $final[$value] = null;
                                            }
                                            break;
                                    }
                                }
                            }
                            $parametros = json_decode($planilha->parametros_json);
                            foreach ($parametros as $indice => $valor) {
                                $final[$indice] = $valor ;
                            }

                        }
                        $codigo_quebrado = explode(".", $final['codigo_insumo']);

                        # se for grupo, subgrupo
                        if(strlen($codigo_quebrado[0]) <= 45) {
                            if (count($codigo_quebrado) <= 4) {
                                if (count($codigo_quebrado) === 1) {
                                    $grupo = Grupo::where('codigo', $final['codigo_insumo'])->first();
                                    if($grupo){
                                        if($grupo->nome != $final['descricao']){
                                            $erro = 1;
                                            $mensagens_erro[] = 'Já existe o grupo '.'
                                                <span style="color:orange">'.$grupo->codigo.' - '.$grupo->nome.'</span>
                                                e você tentou inserir '.'
                                                "<span style="color:red">'.$final['codigo_insumo'].' - '.$final['descricao'].'</span>"';
                                        }
                                    }else {
                                        Grupo::create([
                                            'codigo' => $final['codigo_insumo'],
                                            'nome' => $final['descricao']
                                        ]);
                                    }
                                } else {
                                    $codigo_subgrupo = $codigo_quebrado;
                                    $subGrupo = Grupo::where('codigo', implode('.', $codigo_subgrupo))->first();

                                    if ($subGrupo) {
                                        if($subGrupo->nome != $final['descricao']){
                                            $erro = 1;
                                            $mensagens_erro[] = 'Já existe o subGrupo '.'
                                                <span style="color:orange">'.$subGrupo->codigo.' - '.$subGrupo->nome.'</span>
                                                e você tentou inserir '.'
                                                "<span style="color:red">'.$final['codigo_insumo'].' - '.$final['descricao'].'</span>"';
                                        }
                                    }else{
                                        $codigo_grupo_pai = $codigo_quebrado;
                                        # array_pop() extrai e retorna o último elemento de array, diminuindo array em um elemento.
                                        array_pop($codigo_grupo_pai);
                                        $grupoPai = Grupo::where('codigo', implode('.', $codigo_grupo_pai))->first();

                                        Grupo::create([
                                            'codigo' => $final['codigo_insumo'],
                                            'nome' => $final['descricao'],
                                            'grupo_id' => $grupoPai->id
                                        ]);
                                    }
                                }
                            } # se for serviço
                            elseif (count($codigo_quebrado) == 5) {
                                $servico = Servico::where('codigo', $final['codigo_insumo'])->first();
                                if($servico){
                                    if($servico->nome != $final['descricao']){
                                        $erro = 1;
                                        $mensagens_erro[] = 'Já existe o grupo '.'
                                            <span style="color:orange">'.$servico->codigo.' - '.$servico->nome.'</span>
                                            e você tentou inserir '.'
                                            "<span style="color:red">'.$final['codigo_insumo'].' - '.$final['descricao'].'</span>"';
                                    }
                                }else {
                                    $codigo_grupo_pai = $codigo_quebrado;
                                    array_pop($codigo_grupo_pai);
                                    $grupoPai = Grupo::where('codigo', implode('.', $codigo_grupo_pai))->first();
                                    if ($grupoPai) {
                                        Servico::create([
                                            'codigo' => $final['codigo_insumo'],
                                            'nome' => $final['descricao'],
                                            'grupo_id' => $grupoPai->id
                                        ]);
                                    }
                                }
                            } # se for insumo
                            elseif (count($codigo_quebrado) == 6) {
                                #quebrar codigo insumo explode(separar os pontos)
                                if ($final['codigo_insumo']) {
                                    $codigo_insumo = explode(".", $final['codigo_insumo']);
                                    $codigo_grupo = $codigo_insumo[0];
                                    $codigo_subgrupo1 = $codigo_insumo[0] . '.' . $codigo_insumo[1];
                                    $codigo_subgrupo2 = $codigo_insumo[0] . '.' . $codigo_insumo[1] . '.' . $codigo_insumo[2];
                                    $codigo_subgrupo3 = $codigo_insumo[0] . '.' . $codigo_insumo[1] . '.' . $codigo_insumo[2] . '.' . $codigo_insumo[3];
                                    $codigo_servico = $codigo_insumo[0] . '.' . $codigo_insumo[1] . '.' . $codigo_insumo[2] . '.' . $codigo_insumo[3] . '.' . $codigo_insumo[4];
                                    $codigo_insumo = $codigo_insumo[5];
                                }

                                # query verificar se existe grupo_id = obra
                                $grupo = Grupo::where('codigo', $codigo_grupo)->first();

                                if ($grupo) {
                                    $final['grupo_id'] = $grupo->id;
                                } else {
                                    $erro = 1;
                                    $mensagens_erro[] = 'grupo - Código: ' . $codigo_grupo . ' não foi encontrado.';
                                }

                                # query verificar se existe subgrupo1_id
                                $subgrupo1 = Grupo::where('codigo', $codigo_subgrupo1)->first();

                                if ($subgrupo1) {
                                    $final['subgrupo1_id'] = $subgrupo1->id;
                                } else {
                                    $erro = 1;
                                    $mensagens_erro[] = 'subgrupo1 - Código: ' . $codigo_subgrupo1 . ' não foi encontrado.';
                                }

                                # query verificar se existe subgrupo2_id
                                $subgrupo2 = Grupo::where('codigo', $codigo_subgrupo2)->first();

                                if ($subgrupo2) {
                                    $final['subgrupo2_id'] = $subgrupo2->id;
                                } else {
                                    $erro = 1;
                                    $mensagens_erro[] = 'subgrupo2 - Código: ' . $codigo_subgrupo2 . ' não foi encontrado.';
                                }

                                # query verificar se existe subgrupo3_id
                                $subgrupo3 = Grupo::where('codigo', $codigo_subgrupo3)->first();

                                if ($subgrupo3) {
                                    $final['subgrupo3_id'] = $subgrupo3->id;
                                } else {
                                    $erro = 1;
                                    $mensagens_erro[] = 'subgrupo3 - Código: ' . $codigo_subgrupo3 . ' não foi encontrado.';
                                }

                                # query verificar se existe servico_id
                                $servico = Servico::where('codigo', $codigo_servico)->first();

                                if ($servico) {
                                    $final['servico_id'] = $servico->id;
                                } else {
                                    $erro = 1;
                                    $mensagens_erro[] = 'Serviço - Código: ' . $codigo_servico . ' não foi encontrado.';
                                }

                                # query verificar se existe insumo_id
                                $insumo = Insumo::where('codigo', $codigo_insumo)->first();

                                if ($insumo) {
                                    if (!$insumo->active) {
                                        $erro = 1;
                                        $mensagens_erro[] = 'Insumo - Código: ' . $codigo_insumo . ' não está disponivel.';
                                    } else if (!$insumo->grupo->active) {
                                        $erro = 1;
                                        $mensagens_erro[] = 'Insumo - Código: ' . $codigo_insumo . ' faz parte de um grupo indisponível.';
                                    } else {
                                        $final['insumo_id'] = $insumo->id;
                                    }
                                } else {
                                    $erro = 1;
                                    $mensagens_erro[] = 'Insumo - Código: ' . $codigo_insumo . ' não foi encontrado.';
                                }

                                #Amarra serviço ao insumo
                                if (isset($final['servico_id']) && isset($final['insumo_id'])) {
                                    $insumo_servico = InsumoServico::firstOrCreate([
                                        'servico_id' => $final['servico_id'],
                                        'insumo_id' => $final['insumo_id']
                                    ]);
                                }

                                # save data table budget
                                if ($erro == 0) {
                                    # Valida se o preço unitário do item é maior que 0
                                    if($final['preco_unitario'] > 0) {
                                        Orcamento::create($final);
                                    }
                                } else {
                                    // estourar loop
                                    $erro = 1;
                                    break;
                                }
                            }
                        }else{
                            $erro = 1;
                            $mensagens_erro[] = 'Template não contém a mesma posição ou colunas da planilha importada.';
                        }

                        if($erro == 1){
                            break;
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

    public static function planejamento($planilha){
        $line = 1;
        $reader = ReaderFactory::create(Type::XLSX);
        $reader->setShouldFormatDates(true);
        $reader->open(str_replace('public','storage/app/',public_path()).$planilha->arquivo);

        $folha = 0;
        $erro = 0;
        $mensagens_erro = [];
        $final = [];
        \DB::beginTransaction();

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
                                    switch (Planejamento::$relation[$value]) {
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
                                        case 'date' :
                                            $data_array = explode('/',$row[$chave]);
                                            if(count($data_array)==3){
                                                if($data_array[0] > 12){
                                                    $final[$value] = $data_array[2].'-'.$data_array[1].'-'.$data_array[0];
                                                } else {
                                                    $final[$value] = $data_array[2] . '-' . $data_array[0] . '-' . $data_array[1];
                                                }
                                            }else{
                                                $erro = 1;
                                                $mensagens_erro[] = 'O campo ' . $value . ' não é do tipo DATE';
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
                                $final[$indice] = $valor;
                            }

                        }

                        # save data table budget
                        if($erro == 0) {
                            Planejamento::updateOrCreate(
                              [
                                  'obra_id' => $final['obra_id'],
                                  'resumo' => $final['resumo'],
                                  'tarefa' => trim($final['tarefa'])
                              ],
                              [
                                  'user_id' => $final['user_id'],
                                  'prazo' => $final['prazo'],
                                  'data' => $final['data'],
                                  'data_fim' => $final['data_fim'],
                                  'data_upload' => date('Y-m-d')
                              ]
                            );
//                            Planejamento::create($final);
                        }else{
                            // estourar loop
                            $erro = 1;
                            break;
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
