<?php

namespace App\Http\Controllers;

use App\DataTables\NotafiscalDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateNotafiscalRequest;
use App\Http\Requests\UpdateNotafiscalRequest;
use App\Models\Contrato;
use App\Repositories\ConsultaNfeRepository;
use App\Repositories\NotafiscalRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response;

class NotafiscalController extends AppBaseController
{
    /** @var  NotafiscalRepository */
    private $notafiscalRepository;

    public function __construct(NotafiscalRepository $notafiscalRepo)
    {
        $this->notafiscalRepository = $notafiscalRepo;
    }

    /**
     * Display a listing of the Notafiscal.
     *
     * @param NotafiscalDataTable $notafiscalDataTable
     * @return Response
     */
    public function index(NotafiscalDataTable $notafiscalDataTable)
    {
        return $notafiscalDataTable->render('notafiscals.index');
    }

    /**
     * Show the form for creating a new Notafiscal.
     *
     * @return Response
     */
    public function create()
    {
        $contrato = Contrato::select([
            'contratos.id',
            DB::raw("CONCAT('Contrato: ', contratos.id, ' - ','Fornecedor: ', fornecedores.nome) as nome")
            ])
            ->join('fornecedores','fornecedores.id', '=', 'contratos.fornecedor_id')
            ->pluck('nome','contratos.id')->toArray();
        return view('notafiscals.create', compact('contrato'));
    }

    /**
     * Store a newly created Notafiscal in storage.
     *
     * @param CreateNotafiscalRequest $request
     *
     * @return Response
     */
    public function store(CreateNotafiscalRequest $request)
    {
        $input = $request->all();

        $notafiscal = $this->notafiscalRepository->create($input);

        Flash::success('Notafiscal '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('notafiscals.index'));
    }

    /**
     * Display the specified Notafiscal.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal '.trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        return view('notafiscals.show')->with('notafiscal', $notafiscal);
    }

    /**
     * Show the form for editing the specified Notafiscal.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal '.trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        return view('notafiscals.edit')->with('notafiscal', $notafiscal);
    }

    /**
     * Update the specified Notafiscal in storage.
     *
     * @param  int              $id
     * @param UpdateNotafiscalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNotafiscalRequest $request)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal '.trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        $notafiscal = $this->notafiscalRepository->update($request->all(), $id);

        Flash::success('Notafiscal '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('notafiscals.index'));
    }

    /**
     * Remove the specified Notafiscal from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $notafiscal = $this->notafiscalRepository->findWithoutFail($id);

        if (empty($notafiscal)) {
            Flash::error('Notafiscal '.trans('common.not-found'));

            return redirect(route('notafiscals.index'));
        }

        $this->notafiscalRepository->delete($id);

        Flash::success('Notafiscal '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('notafiscals.index'));
    }

    /**
     * printDebug
     * Adiciona descrição do erro ao contenedor dos erros
     *
     * @name printDebug
     * @param   string $msg Descrição do erro
     * @return  none
     */
    public function  printDebug($value) {
        echo sprintf('<pre>%s</pre>', print_r($value, true));
    }

    private function pGunzip1($data)
    {
        $len = strlen($data);
        if ($len < 18 || strcmp(substr($data, 0, 2), "\x1f\x8b")) {
            $msg = "Não é dado no formato GZIP.";
            $this->printDebug($msg);
            return false;
        }
        $method = ord(substr($data, 2, 1));  // metodo de compressão
        $flags  = ord(substr($data, 3, 1));  // Flags
        if ($flags & 31 != $flags) {
            $msg = "Não são permitidos bits reservados.";
            $this->printDebug($msg);
            return false;
        }
        // NOTA: $mtime pode ser negativo (limitações nos inteiros do PHP)
        $mtime = unpack("V", substr($data, 4, 4));
        $mtime = $mtime[1];
        $headerlen = 10;
        $extralen  = 0;
        $extra     = "";
        if ($flags & 4) {
            // dados estras prefixados de 2-byte no cabeçalho
            if ($len - $headerlen - 2 < 8) {
                $msg = "Dados inválidos.";
                $this->printDebug($msg);
                return false;
            }
            $extralen = unpack("v", substr($data, 8, 2));
            $extralen = $extralen[1];
            if ($len - $headerlen - 2 - $extralen < 8) {
                $msg = "Dados inválidos.";
                $this->printDebug($msg);
                return false;
            }
            $extra = substr($data, 10, $extralen);
            $headerlen += 2 + $extralen;
        }
        $filenamelen = 0;
        $filename = "";
        if ($flags & 8) {
            // C-style string
            if ($len - $headerlen - 1 < 8) {
                $msg = "Dados inválidos.";
                $this->printDebug($msg);
                return false;
            }
            $filenamelen = strpos(substr($data, $headerlen), chr(0));
            if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
                $msg = "Dados inválidos.";
                $this->printDebug($msg);
                return false;
            }
            $filename = substr($data, $headerlen, $filenamelen);
            $headerlen += $filenamelen + 1;
        }
        $commentlen = 0;
        $comment = "";
        if ($flags & 16) {
            // C-style string COMMENT data no cabeçalho
            if ($len - $headerlen - 1 < 8) {
                $msg = "Dados inválidos.";
                $this->printDebug($msg);
                return false;
            }
            $commentlen = strpos(substr($data, $headerlen), chr(0));
            if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
                $msg = "Formato de cabeçalho inválido.";
                $this->printDebug($msg);
                return false;
            }
            $comment = substr($data, $headerlen, $commentlen);
            $headerlen += $commentlen + 1;
        }
        $headercrc = "";
        if ($flags & 2) {
            // 2-bytes de menor ordem do CRC32 esta presente no cabeçalho
            if ($len - $headerlen - 2 < 8) {
                $msg = "Dados inválidos.";
                $this->printDebug($msg);
                return false;
            }
            $calccrc = crc32(substr($data, 0, $headerlen)) & 0xffff;
            $headercrc = unpack("v", substr($data, $headerlen, 2));
            $headercrc = $headercrc[1];
            if ($headercrc != $calccrc) {
                $msg = "Checksum do cabeçalho falhou.";
                $this->printDebug($msg);
                return false;
            }
            $headerlen += 2;
        }
        // Rodapé GZIP
        $datacrc = unpack("V", substr($data, -8, 4));
        $datacrc = sprintf('%u', $datacrc[1] & 0xFFFFFFFF);
        $isize = unpack("V", substr($data, -4));
        $isize = $isize[1];
        // decompressão
        $bodylen = $len-$headerlen-8;
        if ($bodylen < 1) {
            $msg = "BUG da implementação.";
            $this->printDebug($msg);
            return false;
        }
        $body = substr($data, $headerlen, $bodylen);
        $data = "";
        if ($bodylen > 0) {
            switch ($method) {
                case 8:
                    // Por hora somente é suportado esse metodo de compressão
                    $data = gzinflate($body, null);
                    break;
                default:
                    $msg = "Método de compressão desconhecido (não suportado).";
                    $this->printDebug($msg);
                    return false;
            }
        }  // conteudo zero-byte é permitido
        // Verificar CRC32
        $crc   = sprintf("%u", crc32($data));
        $crcOK = $crc == $datacrc;
        $lenOK = $isize == strlen($data);
        if (!$lenOK || !$crcOK) {
            $msg = ( $lenOK ? '' : 'Verificação do comprimento FALHOU. ').( $crcOK ? '' : 'Checksum FALHOU.');
            $this->printDebug($msg);
            return false;
        }
        return $data;
    }//fim gunzip1

    public function pescadorNfe(){
        //ConsultaNfeRepository::buscaNfe();
        //dd();
        $path = storage_path('app/public/nfe/producao/temporarias/201707/-retDownnfe.xml');
        $xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', file_get_contents($path));

        $resNFe = 'resNFe_v1.00.xsd';
        $procNFe = 'procNFe_v3.10.xsd';

        $resp = array(
            'bStat' => false,
            'cStat' => 0,
            'xMotivo' => '',
            'dhResp' => '',
            'ultNSU' => 0,
            'maxNSU' => 0,
            'docs' => array()
        );

        try {
            //tratar dados de retorno
            $dom = new \DOMDocument('1.0', 'utf-8'); //cria objeto DOM
            $dom->formatOutput = false;
            $dom->preserveWhiteSpace = false;
            $dom->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $retDistDFeInt = $dom->getElementsByTagName("retDistDFeInt")->item(0);
            $cStat = ! empty($dom->getElementsByTagName('cStat')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('cStat')->item(0)->nodeValue : '';
            $xMotivo = ! empty($dom->getElementsByTagName('xMotivo')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';

            $bStat = true;
            $dhResp = ! empty($dom->getElementsByTagName('dhResp')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('dhResp')->item(0)->nodeValue : '';
            $ultNSU = ! empty($dom->getElementsByTagName('ultNSU')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('ultNSU')->item(0)->nodeValue : '';
            $maxNSU = ! empty($dom->getElementsByTagName('maxNSU')->item(0)->nodeValue) ?
                $dom->getElementsByTagName('maxNSU')->item(0)->nodeValue : '';
            $resp = array(
                'bStat' => $bStat,
                'cStat' => (int) $cStat,
                'xMotivo' => (string) $xMotivo,
                'dhResp' => (string) $dhResp,
                'ultNSU' => (int) $ultNSU,
                'maxNSU' => (int) $maxNSU,
                'docs' => array()
            );

            $docs = $dom->getElementsByTagName('docZip');
            foreach ($docs as $doc) {

                $nsu = (int) $doc->getAttribute('NSU');
                $schema = (string) $doc->getAttribute('schema');
                //o conteudo desse dado é um zip em base64
                //para deszipar deve primeiro descomverter de base64
                //e depois aplicar a descompactação
                $zip = (string) $doc->nodeValue;
                $zipdata = base64_decode($zip);
                $zip = $this->pGunzip1($zipdata);

                $aDocs[] = array(
                    'NSU' => $nsu,
                    'schema' => $schema,
                    'dados' => $zip
                );
            }

            // Percorrendo array para popular tabela de notas_fiscais e nota_fiscal_itens
            $resp['docs'] = $aDocs;
            foreach($resp['docs'] as $item){
                // NFe de serviço
                if($item['schema'] = $resNFe){
                    /*
                     * Buscar tags de nota de serviço
                     * salvar no banco de dados
                     * */


                }
                // NFe de produto
                if($item['schema'] = $procNFe){
                    /*
                     * Buscar tags de nota de produto
                     * salvar no banco de dados
                     * */
                }
            }

            $cont = 0;
            foreach($aDocs as $doc) {
               $at = simplexml_load_string($doc['dados']);
                if ($doc['schema'] == 'procNFe_v3.10.xsd')
                    dump($cont++, $at, $doc['NSU'],  $doc['schema']);


            }

            die();

        } catch (\Exception $e) {
           $erro = $this->printDebug($e->getMessage());
            return $erro;
        }//fim catch
    }
}
