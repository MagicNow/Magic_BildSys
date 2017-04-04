<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 04/04/2017
 * Time: 11:27
 */

namespace App\Repositories\Admin;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Flash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class SpreadsheetRepository
{
    public static function Spreadsheet($spreadsheet, $type){
//        try{
            /* Verifica se foi selecionado algum arquivo e salva na tabela *planilhas* */
            if ($spreadsheet) {
                # Pegando nome do arquivo
                $nome = str_slug($spreadsheet->getClientOriginalName());
                # pegando nome e contatenando com extensão e rand
                $nome =  substr($nome,0,strlen($nome)-3).rand(1000, 99999).'.'.$spreadsheet->getClientOriginalExtension();
                # Salvando o arquivo na pasta storage/app/public
                $destinationPath = $spreadsheet->storeAs('public/planilhas', $nome);

                $line = 1;
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
                                dd($cabecalho);
                            } else {
                                $line++;

                                # processo de planilha aqui

                            }
                            // do stuff with the row
                        }
                    }
                    $folha++;
                }
                $reader->close();

                return view('admin.planilhas.import');
            }

            \Flash::error('Escolha um arquivo');
            return back()->with('error', 'Escolha um arquivo!');
//        }catch(\Exception $e) {
//            \Flash::info('Confira o modelo da planilha, pois há divergências.');
//            return Redirect::back();
//        }
    }

}