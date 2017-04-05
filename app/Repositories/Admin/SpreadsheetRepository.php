<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 04/04/2017
 * Time: 11:27
 */

namespace App\Repositories\Admin;

use App\Models\Orcamento;
use App\Models\Planilha;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Flash;
use Illuminate\Support\Facades\Redirect;

class SpreadsheetRepository
{
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
                                $columns = Orcamento::$relation;
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

    public static function SpreadsheetProcess($planilha)
    {
        $line = 1;
        $reader = ReaderFactory::create(Type::CSV);
        $reader->setFieldDelimiter(';');
        $reader->setEndOfLineCharacter("\r");
        $reader->setEncoding('UTF-8');
        $reader->open(str_replace('public','storage/app/',public_path()).$planilha->arquivo);

        $folha = 0;
        foreach ($reader->getSheetIterator() as $sheet) {
            if ($folha === 0) {
                $linha = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    $linha++;
                    if ($linha > 1) {
                        $line++;
                        $erro = 0;

                        $colunas = json_decode($planilha->colunas_json);
//                        dd($colunas);
                        \DB::beginTransaction();
                        foreach ($colunas as $chave => $value) {
                            if($value) {
                                switch ($chave) {
                                    case Orcamento::$relation[$value] == 'string' :
                                        if (is_string($row[$chave])) {
                                            $final[$value] = $row[$chave];
                                        } else {
                                            if((string) ($row[$chave])) {
                                                $final[$value] = (string)($row[$chave]);
                                            } else {
                                                $erro = 1;
                                            }
                                        }
                                        break;

                                    case Orcamento::$relation[$value] == 'decimal' :
                                        if (is_float($row[$chave])) {
                                            $final[$value] = $row[$chave];
                                        } else {
                                            if(floatval($row[$chave])) {
                                                $final[$value] = floatval($row[$chave]);
                                            } else {
                                                $erro = 1;
                                            }
                                        }
                                        break;

                                    case Orcamento::$relation[$value] == 'integer' :
                                        if (is_int($row[$chave])) {
                                            $final[$value] = $row[$chave];
                                        } else {
                                            if((int) ($row[$chave])) {
                                                $final[$value] = (int) ($row[$chave]);
                                            } else {
                                                $erro = 1;
                                            }
                                        }
                                        break;
                                }
                            }
                            $parametros = json_decode($planilha->parametros_json);
                            foreach ($parametros as $indice => $valor) {
                                $final[$indice] = $valor ;
                            }

                        }
                        $orcamento = Orcamento::create($final);
                        if($erro == 0) {
                            \DB::commit();
                        }else{
                            \DB::rollBack();
                        }
                    }
                }
            }
            $folha++;
        }
        $reader->close();

    }
}