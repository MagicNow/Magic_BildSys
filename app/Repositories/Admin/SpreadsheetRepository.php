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
use Illuminate\Support\Facades\Storage;

class SpreadsheetRepository
{
    public static function Spreadsheet($spreadsheet, $type){
        try{
            /* Verifica se foi selecionado algum arquivo e salva na tabela *planilhas* */
            if ($spreadsheet) {
                # Pegando nome do arquivo
                $nome = str_slug($spreadsheet->getClientOriginalName());
                # pegando nome e contatenando com extensão e rand
                $nome =  substr($nome,0,strlen($nome)-3).rand(1000, 99999).'.'.$spreadsheet->getClientOriginalExtension();
                # Salvando o arquivo na pasta storage/app/public
                $destinationPath = $spreadsheet->storeAs('public/planilhas', $nome);

                $planilha = new Planilha();
                $planilha->user_id = \Auth::id();
                $planilha->arquivo = $destinationPath;
                $planilha->tipo_orcamento_id = $type;
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

                        $json_decode = json_decode($planilha->json);
//                        dd($json_decode);
                        \DB::beginTransaction();
                        foreach ($json_decode as $chave => $value) {
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

                            $orcamento = Orcamento::create($final);


                        }
                        dd($final);
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