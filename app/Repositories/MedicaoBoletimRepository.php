<?php

namespace App\Repositories;

use App\Models\MedicaoBoletim;
use App\Models\MedicaoBoletimStatusLog;
use App\Notifications\NotificaFornecedorMedicaoBoletim;
use Illuminate\Support\Facades\Mail;
use InfyOm\Generator\Common\BaseRepository;
use PDF;

class MedicaoBoletimRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'obra_id',
        'contrato_id',
        'medicao_boletim_status_id',
        'obs',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MedicaoBoletim::class;
    }

    public function create(array $attributes)
    {
        $attributes['user_id'] = auth()->id();
        $attributes['medicao_boletim_status_id'] = 1;
        $model = parent::create($attributes);

        // Cria status
        MedicaoBoletimStatusLog::create([
            'medicao_boletim_id' => $model->id,
            'medicao_boletim_status_id' => $model->medicao_boletim_status_id,
            'user_id' => auth()->id()
        ]);

        return $this->parserResult($model);
    }

    public function update(array $attributes, $id)
    {

        $model = parent::update($attributes, $id);

        return $this->parserResult($model);
    }
    
    public function liberaParaNF($id){
        $arquivo = self::geraImpressao($id);
        dd($arquivo);

        $medicaoBoletim = $this->find($id);
        $medicaoBoletim->medicao_boletim_status_id = 2;
        $medicaoBoletim->save();

        MedicaoBoletimStatusLog::create([
            'medicao_boletim_id' => $medicaoBoletim->id,
            'medicao_boletim_status_id' => $medicaoBoletim->medicao_boletim_status_id,
            'user_id' => auth()->id()
        ]);

        /**
         * Enviar e-mail para o fornecedor com o resumo da medição
         * */

        $fornecedor = $medicaoBoletim->contrato->fornecedor;
        $mensagens = [];

        if ($user = $fornecedor->user) {
            //se tiver já envia uma notificação
            $user->notify(new NotificaFornecedorMedicaoBoletim($fornecedor, $arquivo));
            return [
                'success'=>true
            ];
        } else {
            // Se não tiver envia um e-mail para o fornecedor
            if (!strlen($fornecedor->email)) {
                $mensagens[] = 'O Fornecedor ' . $fornecedor->nome . ' não possui acesso e e-mail cadastrado,
                    <a href="'.Storage::url($arquivo).'" target="_blank">Imprima o boletim</a> e entregue ao fornecedor.
                    O telefone do fornecedor é ' . $fornecedor->telefone;
                return [
                    'success'=>true,
                    'messages'=>$mensagens
                ];
            } else {
                Mail::to($fornecedor->email)->send(new ContratoServicoFornecedorNaoUsuario($contrato, $arquivo));
                return [
                    'success'=>true
                ];
            }
        }
    }

    public static function geraImpressao($id)
    {
        $medicaoBoletim = MedicaoBoletim::find($id);
        if (!$medicaoBoletim) {
            return null;
        }

        
        if (is_file(base_path().'/storage/app/public/contratos/boletim_'.$medicaoBoletim->id.'.pdf')) {
            unlink(base_path().'/storage/app/public/contratos/boletim_'.$medicaoBoletim->id.'.pdf');
        }
        PDF::loadView('medicao_boletims.pdf',compact('medicaoBoletim'))->setPaper('a4')->setOrientation('portrait')->save(base_path().'/storage/app/public/contratos/boletim_'.$medicaoBoletim->id.'.pdf');
        return 'contratos/boletim_'.$medicaoBoletim->id.'.pdf';
    }
}
