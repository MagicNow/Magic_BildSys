<?php

namespace App\Repositories;

use App\Mail\ContratoServicoFornecedorNaoUsuario;
use App\Models\MedicaoBoletim;
use App\Models\MedicaoBoletimStatusLog;
use App\Notifications\NotificaFornecedorMedicaoBoletim;
use Illuminate\Support\Facades\DB;
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
        $retornoImpressao = self::geraImpressao($id);
        if(!$retornoImpressao){
            return ['success'=>false];
        }

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
            //$user->notify(new NotificaFornecedorMedicaoBoletim($fornecedor, $retornoImpressao['arquivo']));
            Mail::to($fornecedor->email)->send(new ContratoServicoFornecedorNaoUsuario($medicaoBoletim->contrato, $retornoImpressao['arquivo']));
            return [
                'success'=>true,
            ]+$retornoImpressao;
        } else {
            // Se não tiver envia um e-mail para o fornecedor
            if (!strlen($fornecedor->email)) {
                $mensagens[] = 'O Fornecedor ' . $fornecedor->nome . ' não possui acesso e e-mail cadastrado,
                    <a href="'.Storage::url($retornoImpressao['arquivo']).'" target="_blank">Imprima o boletim</a> e entregue ao fornecedor.
                    O telefone do fornecedor é ' . $fornecedor->telefone;
                return [
                    'success'=>true,
                    'messages'=>$mensagens
                ]+$retornoImpressao;
            } else {
                Mail::to($fornecedor->email)->send(new ContratoServicoFornecedorNaoUsuario($medicaoBoletim->contrato, $retornoImpressao['arquivo']));
                return [
                    'success'=>true
                ]+$retornoImpressao;
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

        $insumosMedidos = $medicaoBoletim->medicaoServicos()->select([
            DB::raw('SUM(medicao_servicos.descontos) descontos'),
            'insumos.id as insumo_id',
            'insumos.codigo',
            'insumos.nome',
            'insumos.unidade_sigla',
            'contrato_itens.id',
            'contrato_itens.qtd',
            'contrato_itens.valor_unitario',
            'contrato_itens.valor_total',
            DB::raw('SUM( (SELECT SUM(qtd) FROM medicoes WHERE medicoes.medicao_servico_id = medicao_servicos.id ) ) as qtd_medida'),
        ])
            ->join('contrato_item_apropriacoes','contrato_item_apropriacao_id','contrato_item_apropriacoes.id')
            ->join('contrato_itens', 'contrato_item_id','contrato_itens.id')
            ->join('insumos', 'contrato_item_apropriacoes.insumo_id', 'insumos.id')
            ->groupBy('insumos.nome')
            ->get();

        // Insumos Não medidos
        $contrato = $medicaoBoletim->contrato;
        $insumosNaoMedidos = $contrato->itens()->select([
            DB::raw('0 as descontos'),
            'insumos.nome',
            'insumos.codigo',
            'insumos.unidade_sigla',
            'contrato_itens.id',
            'contrato_itens.qtd',
            'contrato_itens.valor_unitario',
            'contrato_itens.valor_total',
            DB::raw('0 as qtd_medida')

        ])
            ->join('insumos','insumos.id','contrato_itens.insumo_id')
            ->whereNotIn('insumo_id', $insumosMedidos->pluck('insumo_id','insumo_id')->toArray() )->get();

        PDF::loadView('medicao_boletims.pdf',compact('medicaoBoletim', 'insumosMedidos','insumosNaoMedidos'))
                ->setPaper('a4')->setOrientation('landscape')
                ->setOption('margin-top', 1)
                ->setOption('margin-bottom', 1)
                ->setOption('margin-left', 1)
                ->setOption('margin-right', 1)
                ->setOption('image-dpi',300)
                ->save(base_path().'/storage/app/public/contratos/boletim_'.$medicaoBoletim->id.'.pdf');

        return [
            'arquivo'=>'contratos/boletim_'.$medicaoBoletim->id.'.pdf',
            'insumosMedidos' => $insumosMedidos,
            'insumosNaoMedidos' => $insumosNaoMedidos
                ];
    }
}
