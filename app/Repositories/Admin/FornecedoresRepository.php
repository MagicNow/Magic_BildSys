<?php

namespace App\Repositories\Admin;

use App\Models\Fornecedor;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Repositories\Admin\UserRepository;
use Illuminate\Support\Facades\App;
use App\Models\User;
use App\Notifications\FornecedorAccountCreated;
use Artesaos\Defender\Facades\Defender;
use App\Models\MegaFornecedor;

class FornecedoresRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo_mega',
        'nome',
        'cnpj',
        'tipo_logradouro',
        'logradouro',
        'numero',
        'complemento',
        'cidade_id',
        'municipio',
        'estado',
        'situacao_cnpj',
        'inscricao_estadual',
        'email',
        'site',
        'telefone'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Fornecedor::class;
    }

    /**
     * Retorna os fornecedores que podem preencher um certo quadro em sua rodada
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function todosQuePodemPreencherQuadroNaRodada($quadro_id, $rodada_atual)
    {
        return $this->model->select('fornecedores.*')
            ->whereHas(
                'qcFornecedor',
                function($query) use ($quadro_id, $rodada_atual) {
                    $query->where('quadro_de_concorrencia_id', $quadro_id);
                    $query->where('rodada', $rodada_atual);
                    $query->whereNull('desistencia_motivo_id');
                    $query->whereNull('desistencia_texto');
                    $query->doesntHave('itens');
                }
        )
            ->get();
    }


    /**
     * Retorna se certo fornecedor pode preencher a rodada atual
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function podePreencherQuadroNaRodada($fornecedor_id, $quadro_id, $rodada_atual)
    {
        return (bool) $this->model
            ->whereHas(
                'qcFornecedor',
                function($query) use ($quadro_id, $rodada_atual) {
                    $query->where('quadro_de_concorrencia_id', $quadro_id);
                    $query->where('rodada', $rodada_atual);
                    $query->whereNull('desistencia_motivo_id');
                    $query->whereNull('desistencia_texto');
                    $query->doesntHave('itens');
                }
        )
            ->where('fornecedores.id', $fornecedor_id)
            ->count('fornecedores.id');
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $input)
    {
        $fornecedor = DB::transaction(function() use ($input) {
            $fornecedor = parent::create($input);

            if(Arr::get($input, 'is_user', false)) {
                $user = $this->createOrUpdateFornecedorUser($fornecedor);
                $fornecedor->update(['user_id' => $user->id]);
            }

            return $fornecedor;
        });

        return $fornecedor;
    }

    /**
     * {@inheritDoc}
     */
    public function update(array $input, $id)
    {
        $fornecedor = DB::transaction(function() use ($input, $id)  {
            $fornecedor = parent::update($input, $id);

            if(Arr::get($input, 'is_user', false) || $fornecedor->user_id) {
                $user = $this->createOrUpdateFornecedorUser(
                    $fornecedor,
                    Arr::get($input, 'is_user', false)
                );
                $fornecedor->update(['user_id' => $user->id]);
            }


            return $fornecedor;
        });

        return $fornecedor;
    }

    /**
     * Cria um usuário pro fornecedor
     *
     * @return User
     */
    private function createOrUpdateFornecedorUser(Fornecedor $fornecedor, $active = true)
    {
        $userRepository = App::make(UserRepository::class);

        if($fornecedor->user_id) {
            $user = $userRepository->update([
                'name' => $fornecedor->nome,
                'email' => $fornecedor->email,
                'active' => $active
            ], $fornecedor->user_id);

            return $user;
        }

        $password = str_random(8);

        $user = $userRepository->create([
            'name'     => $fornecedor->nome,
            'email'    => $fornecedor->email,
            'active'   => $active,
            'admin'    => false,
            'password' => $password
        ]);

        $user->attachRole(Defender::findRole('Fornecedor'));

        $user->notify(new FornecedorAccountCreated($password));

        return $user;
    }

    public function comContrato()
    {
        return $this->model->has('contratos')->get();
    }

    public function updateImposto($id)
    {
        $fornecedor = $this->find($id);

        if(is_null($fornecedor->imposto_simples) && $fornecedor->codigo_mega) {
            $fornecedor->imposto_simples = trim(utf8_encode(MegaFornecedor::where('AGN_IN_CODIGO', $fornecedor->codigo_mega)
                ->value('AGN_BO_SIMPLES'))) === 'S';

            $fornecedor->save();
        }

        return $fornecedor;
    }
}
