<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\WorkflowAlcada;
use App\Models\WorkflowAprovacao;
use App\Models\WorkflowUsuario;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\String_;
use App\Models\WorkflowTipo;
use App\Models\Contrato;
use App\Models\ContratoItemModificacao;

class WorkflowAprovacaoRepository
{
    /**
     * verificaAprovacoes de um item e responde se o usuário pode ou não aprovar
     * @param $tipo
     * @param $id
     * @param User $user
     * @return array [
         'podeAprovar' => boolean,
         'iraAprovar' => boolean,
         'jaAprovou' => boolean,
         'aprovacao' => boolean,
         'msg' => string
     ]
     */
    public static function verificaAprovacoes($tipo, $id, User $user)
    {
        eval('$workflow_tipo_id= \\App\Models\\' . $tipo . '::$workflow_tipo_id;');
        eval('$obj = \\App\Models\\' . $tipo . '::find(' . $id . ');');

        $workflow_tipo = WorkflowTipo::find($workflow_tipo_id);

        if ($obj) {
            $ids = $obj->irmaosIds();

            // Busca alçada atual
            $alcada_atual = self::verificaAlcadaAtual($tipo, $ids, $workflow_tipo);


            // Verifica se o usuário atual é um aprovador de alguma alçada
            $workflowUsuario = WorkflowUsuario::select(['workflow_usuarios.*', 'workflow_alcadas.ordem'])
                ->join('workflow_alcadas', 'workflow_alcadas.id', '=', 'workflow_usuarios.workflow_alcada_id')
                ->where('workflow_alcadas.workflow_tipo_id', $workflow_tipo_id)// Tipo = Aprovação de OC
                ->where('user_id', $user->id)
                ->where('workflow_alcadas.id', $alcada_atual->id)
                ->first();


            if (!$workflowUsuario) {
                $workflowUsuario = WorkflowUsuario::select(['workflow_usuarios.*', 'workflow_alcadas.ordem'])
                    ->join('workflow_alcadas', 'workflow_alcadas.id', '=', 'workflow_usuarios.workflow_alcada_id')
                    ->where('workflow_alcadas.workflow_tipo_id', $workflow_tipo_id)// Tipo = Aprovação de OC
                    ->where('user_id', $user->id)
                    ->first();

                if ($workflowUsuario) {
                    return [
                        'podeAprovar' => true,
                        'iraAprovar' => false,
                        'jaAprovou' => false,
                        'aprovacao' => null,
                        'msg' => 'A alçada atual de aprovação não é a qual você pertence.'
                    ];
                }

                // Já vaza
                return [
                    'podeAprovar' => false
                ];
            }


            // Verifica se a já é a alçada atual que o usuário está já aprovou
            $jaAprovou = $obj->aprovacoes()
                ->where('user_id', $user->id)
                ->where('workflow_alcada_id', $alcada_atual->id)
                ->first();


            if ($jaAprovou) {
                return [
                    'podeAprovar' => true,
                    'iraAprovar' => false,
                    'jaAprovou' => true,
                    'aprovacao' => $jaAprovou->aprovado,
                    'msg' => null
                ];
            }

            // Verifica se a alçada dele é a primeira
            if ($workflowUsuario->ordem === 1) {
                return [
                    'podeAprovar' => true,
                    'iraAprovar' => true,
                    'jaAprovou' => false,
                    'aprovacao' => null,
                    'msg' => null
                ];
            }

            // Caso não é a primeira, verifica as aprovações da alçada anterior
            $workflowAlcada = WorkflowAlcada::where('workflow_alcadas.workflow_tipo_id', $workflow_tipo_id)// Tipo = Aprovação de OC
                ->where('ordem', ($workflowUsuario->ordem - 1))
                ->first();

            $usuariosAlcadaAnterior = $workflowAlcada->workflowUsuarios()->count();


            # Busca a quantidade de aprovações q este item tem
            $aprovacoesAlcadaAnterior = $obj->aprovacoes()
                ->where('workflow_alcada_id', $workflowAlcada->id)
                ->where('created_at', '>=', $obj->updated_at)
                ->where('aprovado', '=', 1)
                ->count();

            // Se a quantidade de usuários é maior do que as aprovações / reprovações
            if ($usuariosAlcadaAnterior > $aprovacoesAlcadaAnterior) {
                return [
                    'podeAprovar' => true,
                    'iraAprovar' => false,
                    'jaAprovou' => false,
                    'aprovacao' => null,
                    'msg' => 'Ainda falta aprovações da alçada anterior para que você possa aprovar'
                ];
            }

            return [
                'podeAprovar' => true,
                'iraAprovar' => true,
                'jaAprovou' => false,
                'aprovacao' => null,
                'msg' => null
            ];
        }

        return [
            'podeAprovar' => false,
            'iraAprovar' => false,
            'jaAprovou' => false,
            'aprovacao' => null,
            'msg' => 'Item não encontrado'
        ];
    }

    /**
     * Verifica a Alçada Atual do Aprovável
     * @param $tipo
     * @param $ids
     * @param $workflow_tipo_id
     * @return null
     */
    public static function verificaAlcadaAtual($tipo, $ids, WorkflowTipo $workflow_tipo)
    {
        // Pega a qtd de alçadas
        $workflow_alcadas = WorkflowAlcada::where('workflow_tipo_id', $workflow_tipo->id)
            ->orderBy('ordem', 'ASC')
            ->get();

        $alcada_atual_ordem = 1;
        $alcada_atual = null;
        $total_a_aprovar = self::verificaTotalaAprovar($tipo, $ids);

        // Percorre buscando qual alçada está
        foreach ($workflow_alcadas as $alcada) {
            if ($alcada_atual_ordem == $alcada->ordem) {
                $alcada_atual = $alcada;
            }

            $alcada_atual_ordem = $alcada->ordem;

            // Verifica a qtd de avaliações desta alçada
            $avaliado = self::verificaTotalJaAprovadoReprovado($tipo, $ids, null, null, $alcada->id);

            // Pega a qtd de aprovadores desta alçada
            $aprovadores = $alcada->workflowUsuarios()->count();

            $itens_avaliados = 0;

            if ($avaliado['total_avaliado'] > 0) {
                $itens_avaliados = $avaliado['total_avaliado'] / $aprovadores;
            }

            if ($itens_avaliados == $total_a_aprovar) {
                $alcada_atual_ordem = $alcada->ordem + 1;
            }

            if ($workflow_tipo->usa_valor_minimo && $alcada_atual) {
                $class = "App\\Models\\{$tipo}";
                $item = $class::whereIn('id', $ids)->first();

                $alcada_aux = (float) $item->valor_total >= (float) $alcada_atual->valor_minimo
                    ? $alcada_atual
                    : $alcada_atual->anterior();

                $alcada_atual = $alcada_aux ?: $alcada_atual;
            }
        }

        return $alcada_atual;
    }

    /**
     * Verifica a aprovação do grupo completo do tipo aprovável
     * @param $tipo
     * @param $ids
     * @param User $user
     * @return array [ Boolean podeAprovar, Boolean iraAprovar, Boolean jaAprovou, Boolean/Null aprovacao, String msg ]
     */
    public static function verificaAprovaGrupo($tipo, $ids, User $user)
    {
        eval('$workflow_tipo_id= \\App\Models\\' . $tipo . '::$workflow_tipo_id;');
        $workflow_tipo =  WorkflowTipo::find($workflow_tipo_id);

        // Verifica se o usuário atual é um aprovador de alguma alçada
        $workflowUsuario = WorkflowUsuario::select(['workflow_usuarios.*', 'workflow_alcadas.ordem'])
            ->join('workflow_alcadas', 'workflow_alcadas.id', '=', 'workflow_usuarios.workflow_alcada_id')
            ->where('workflow_alcadas.workflow_tipo_id', $workflow_tipo_id)// Tipo = Aprovação de OC
            ->where('user_id', $user->id)
            ->first();

        if (!$workflowUsuario) {
            // Já vaza
            return [
                'podeAprovar' => false
            ];
        }

        $tipo_txt = 'App\\\\Models\\\\' . $tipo;

        eval('$model= \\App\Models\\' . $tipo . '::firstOrNew([]);');
        $tabela = $model->getTable();

        $total_a_aprovar = self::verificaTotalaAprovar($tipo, $ids);

        if ($total_a_aprovar) {

            // Busca alçada atual
            $alcada_atual = self::verificaAlcadaAtual($tipo, $ids, $workflow_tipo);

            $workflowUsuarioAlcadaAtual = WorkflowUsuario::select(['workflow_usuarios.*', 'workflow_alcadas.ordem'])
                ->join('workflow_alcadas', 'workflow_alcadas.id', '=', 'workflow_usuarios.workflow_alcada_id')
                ->where('workflow_alcadas.workflow_tipo_id', $workflow_tipo_id)// Tipo = Aprovação de OC
                ->where('user_id', $user->id)
                ->where('workflow_alcada_id', $alcada_atual->id)
                ->first();

            if (!$workflowUsuarioAlcadaAtual) {
                // Já vaza
                return [
                    'podeAprovar' => false,
                    'iraAprovar' => false,
                    'jaAprovou' => true,
                    'aprovacao' => null,
                    'msg' => 'Você não é aprovador desta alçada ('.$alcada_atual->nome.')'
                ];
            }

            // Verifica se o mesmo já aprovou todas os itens
            $total_aprovados_reprovados = self::verificaTotalJaAprovadoReprovado($tipo, $ids, $user, null, $alcada_atual->id);
            $total_aprovados_reprovados_pelo_user = $total_aprovados_reprovados['total_avaliado'];
            $total_aprovados_pelo_user = $total_aprovados_reprovados['total_aprovado'];

            if ($total_aprovados_reprovados_pelo_user == $total_a_aprovar) {
                return [
                    'podeAprovar' => true,
                    'iraAprovar' => false,
                    'jaAprovou' => true,
                    'aprovacao' => ($total_aprovados_reprovados_pelo_user == $total_aprovados_pelo_user),
                    'msg' => null
                ];
            }

            // Verifica se a alçada dele é a primeira
            if ($workflowUsuario->ordem === 1) {
                return [
                    'podeAprovar' => true,
                    'iraAprovar' => true,
                    'jaAprovou' => false,
                    'aprovacao' => null,
                    'msg' => null
                ];
            }
            // Caso não é a primeira, verifica as aprovações da alçada anterior
            $workflowAlcada = WorkflowAlcada::where('workflow_alcadas.workflow_tipo_id', $workflow_tipo_id)// Tipo = Aprovação de OC
                ->where('ordem', ($workflowUsuario->ordem - 1))
                ->first();

            $usuariosAlcadaAnterior = $workflowAlcada->workflowUsuarios()->count();

            eval('$total_itens_aprovados_reprovados_alc_anterior = \\App\Models\\' . $tipo . '::where(\''. $tabela .'.id\', \'>\', 0);');
            $total_itens_aprovados_reprovados_alc_anterior->join('workflow_aprovacoes', function ($join) use ($tipo_txt, $tabela) {
                $join->on('workflow_aprovacoes.aprovavel_type', '=', DB::raw("'".$tipo_txt ."'"));
                $join->on('workflow_aprovacoes.aprovavel_id', '=', $tabela.'.id');
            })
                ->where('workflow_aprovacoes.workflow_alcada_id', $workflowAlcada->id)
                ->where('workflow_aprovacoes.created_at', '>=', DB::raw($tabela .".updated_at"))
                ->whereIn($tabela.'.id', $ids);
            $total_aprovados_reprovados_alcada_anterior = $total_itens_aprovados_reprovados_alc_anterior->count();

            $aprovacoesAlcadaAnterior = $total_aprovados_reprovados_alcada_anterior / $usuariosAlcadaAnterior;

            // Se a quantidade de usuários é maior do que as aprovações / reprovações
            if ($total_a_aprovar != $aprovacoesAlcadaAnterior) {
                return [
                    'podeAprovar' => true,
                    'iraAprovar' => false,
                    'jaAprovou' => false,
                    'aprovacao' => null,
                    'msg' => 'Ainda falta aprovações da alçada anterior para que você possa aprovar/reprovar todos ao mesmo tempo'
                ];
            }

            return [
                'podeAprovar' => true,
                'iraAprovar' => true,
                'jaAprovou' => false,
                'aprovacao' => null,
                'msg' => null
            ];
        }

        return [
            'podeAprovar' => false,
            'iraAprovar' => false,
            'jaAprovou' => false,
            'aprovacao' => null,
            'msg' => 'Item não encontrado'
        ];
    }

    /**
     * AprovaReprovaItem
     * @param string $tipo
     * @param integer $id
     * @param User $user
     * @param $resposta
     * @param integer or null $motivo_id
     * @param string or null $justificativa
     * @return bool
     */
    public static function aprovaReprovaItem($tipo, $id, User $user, $resposta, $motivo_id = null, $justificativa = null)
    {
        eval('$obj = \\App\\Models\\' . $tipo . '::find(' . $id . ');');
        eval('$workflow_tipo_id= \\App\Models\\' . $tipo . '::$workflow_tipo_id;');

        $workflow_tipo = WorkflowTipo::find($workflow_tipo_id);

        if (!$obj) {
            return false;
        }

        $podeAprovar = self::verificaAprovacoes($tipo, $id, $user);

        if (!$podeAprovar['iraAprovar']) {
            return false;
        }

        $workflowUsuario = WorkflowUsuario::select(['workflow_usuarios.*', 'workflow_alcadas.ordem'])
            ->join('workflow_alcadas', 'workflow_alcadas.id', '=', 'workflow_usuarios.workflow_alcada_id')
            ->where('workflow_alcadas.workflow_tipo_id', $workflow_tipo_id)// Tipo = Aprovação de OC
            ->where('user_id', $user->id)
            ->first();

        DB::beginTransaction();
        try {
            $workflowAprovacao = new WorkflowAprovacao([
                'workflow_alcada_id' => $workflowUsuario->workflow_alcada_id,
                'user_id' => $user->id,
                'aprovado'=> $resposta,
                'workflow_reprovacao_motivo_id' => intval($motivo_id)?$motivo_id:null,
                'justificativa' => strlen($justificativa)?$justificativa:null
            ]);

            $salvo = $obj->aprovacoes()->save($workflowAprovacao);

            // Verifica se é a primeira aprovação deste item dentre os irmãos
            $ids = $obj->irmaosIds();

            $total_ja_votado_geral = self::verificaTotalJaAprovadoReprovado($tipo, $ids);

            $total_ja_votado = self::verificaTotalJaAprovadoReprovado($tipo, $ids, null, $obj->id);

            if ($total_ja_votado_geral['total_avaliado'] === 1) {
                // Se for já altera o status do pai para Em Aprovação
                $obj->paiEmAprovacao();
            }


            // Se não for, verifica se já é a última
            $qtd_aprovadores = self::verificaQuantidadeUsuariosAprovadores($workflow_tipo, $obj->qualObra(), null, $ids, $tipo);

            if ($qtd_aprovadores) {
                // Divide a qtd de aprovações/reprovações pela quantidade de aprovadores
                $avaliacoes = $total_ja_votado['total_avaliado'] / $qtd_aprovadores;

                if ($avaliacoes === 1 || !$workflowAprovacao->aprovado) {
                    // Se for já salva se foi aprovado ou reprovado
                    $obj->aprova($total_ja_votado['total_aprovado'] === $total_ja_votado['total_avaliado']);

                    // Chama função do model do item que irá verificar batendo no pai se todos os filhos foram aprovados
                    $obj->confereAprovacaoGeral();
                }
            }
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return $salvo;
    }

    /**
     * Verifica o total de itens à aprovar
     * @param String_ $tipo
     * @param Array $ids
     * @return Integer
     */
    private static function verificaTotalaAprovar($tipo, $ids)
    {
        eval('$model= \\App\Models\\' . $tipo . '::firstOrNew([]);');

        $tabela = $model->getTable();

        eval('$total_itens_a_aprovar = \\App\Models\\' . $tipo . '::where(\''. $tabela .'.id\', \'>\', 0);');


        $total_itens_a_aprovar->whereIn($tabela.'.id', $ids);

        return $total_itens_a_aprovar->count();
    }

    /**
     * @param $tipo
     * @param $ids
     * @param null $user
     * @param null $item_id
     * @param null $alcada
     * @return array [ Integer total_avaliado, Integer total_aprovado ]
     */
    public static function verificaTotalJaAprovadoReprovado($tipo, $ids, $user = null, $item_id = null, $alcada = null)
    {
        eval('$model= \\App\Models\\' . $tipo . '::firstOrNew([]);');
        $tabela = $model->getTable();

        $tipo_txt = 'App\\\\Models\\\\' . $tipo;

        eval('$total_itens_aprovados_reprovados = \\App\Models\\' . $tipo . '::where(\''. $tabela .'.id\', \'>\', 0);');

        $total_itens_aprovados_reprovados->join('workflow_aprovacoes', function ($join) use ($tipo_txt, $tabela) {
            $join->on('workflow_aprovacoes.aprovavel_type', '=', DB::raw("'".$tipo_txt ."'"));
            $join->on('workflow_aprovacoes.aprovavel_id', '=', $tabela.'.id');
        })
            ->where('workflow_aprovacoes.created_at', '>=', DB::raw($tabela .".updated_at"))
            ->whereIn($tabela.'.id', $ids);

        if ($user) {
            $total_itens_aprovados_reprovados->where('workflow_aprovacoes.user_id', $user->id);
        }
        if ($item_id) {
            $total_itens_aprovados_reprovados->where('workflow_aprovacoes.aprovavel_id', $item_id);
        }

        if ($alcada) {
            $total_itens_aprovados_reprovados->where('workflow_aprovacoes.workflow_alcada_id', $alcada);
        }

        return [
            'total_avaliado' => $total_itens_aprovados_reprovados->count(),
            'total_aprovado' => $total_itens_aprovados_reprovados->where('workflow_aprovacoes.aprovado', 1)->count()
        ];
    }

    /**
     * Verifica a quantidade de usuários aprovadores de uma alçada
     * @param $workflow_tipo_id
     * @param null $obra_id
     * @param null $alcada
     * @return int
     */
    public static function verificaQuantidadeUsuariosAprovadores(WorkflowTipo $workflow_tipo, $obra_id = null, $alcada = null, $ids = null, $tipo = null)
    {
        $qtd_usuarios = 0;

        $workflow_alcadas = WorkflowAlcada::where('workflow_tipo_id', $workflow_tipo->id);

        if ($workflow_tipo->usa_valor_minimo && $ids && $tipo) {
            $class = "App\\Models\\{$tipo}";
            $model = $class::whereIn('id', $ids)->first();
            $workflow_alcadas->where('valor_minimo', '<=', $model->valor_total);
        }

        if ($alcada) {
            $workflow_alcadas->where('id', $alcada);
        }

        $workflow_alcadas = $workflow_alcadas->get();

        foreach ($workflow_alcadas as $alcadas) {
            $queryUsers = $alcadas->workflowUsuarios();

            if ($obra_id) {
                $queryUsers->join('obra_users', 'obra_users.user_id', '=', 'users.id')
                    ->where('obra_users.obra_id', $obra_id);
            }

            $qtd_usuarios += $queryUsers->count();
        }

        return $qtd_usuarios;
    }

    /**
     * Verifica usuários que faltam aprovar
     * @param $tipo
     * @param $workflow_tipo_id
     * @param null $obra_id
     * @param null $alcada
     * @param null $ids
     * @return array
     */
    public static function verificaUsuariosQueFaltamAprovar($tipo, $workflow_tipo_id, $obra_id = null, $alcada = null, $ids = null)
    {
        $usuarios_nomes = [];
        $nomes = [];

        $workflow_alcada = WorkflowAlcada::where('workflow_tipo_id', $workflow_tipo_id)->where('id', $alcada)->first();

        $queryNomes = $workflow_alcada->workflowUsuarios()
            ->select(['users.id','users.name'])
            ->join('obra_users', 'obra_users.user_id', '=', 'users.id');

        if ($obra_id) {
            $queryNomes->where('obra_users.obra_id', $obra_id);
        }

        $usuarios_nomes = $queryNomes->get();

        $total_a_aprovar = self::verificaTotalaAprovar($tipo, $ids);

        foreach ($usuarios_nomes as $usuario) {
            $total_aprovados_reprovados = self::verificaTotalJaAprovadoReprovado($tipo, $ids, $usuario, null, $alcada);
            if ($total_aprovados_reprovados['total_avaliado']!=$total_a_aprovar) {
                $nomes[] = $usuario->name;
            }
        }

        return $nomes;
    }
}
