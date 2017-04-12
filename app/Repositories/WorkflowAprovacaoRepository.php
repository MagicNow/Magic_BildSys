<?php

namespace App\Repositories;


use App\Models\User;
use App\Models\WorkflowAlcada;
use App\Models\WorkflowAprovacao;
use App\Models\WorkflowUsuario;
use Illuminate\Support\Facades\DB;

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
        $aprovador = false;
        // Verifica se o usuário atual é um aprovador de alguma alçada
        $workflowUsuario = WorkflowUsuario::select(['workflow_usuarios.*', 'workflow_alcadas.ordem'])
            ->join('workflow_alcadas', 'workflow_alcadas.id', '=', 'workflow_usuarios.workflow_alcada_id')
            ->where('workflow_alcadas.workflow_tipo_id', 1)// Tipo = Aprovação de OC
            ->where('user_id', $user->id)
            ->first();
        if (!$workflowUsuario) {
            // Já vaza
            return [
                'podeAprovar' => false
            ];
        }

        eval('$obj = \\App\Models\\' . $tipo . '::find(' . $id . ');');
        if ($obj) {

            // Verifica se a já é a alçada atual que o usuário está já aprovou
            $jaAprovou = $obj->aprovacoes()
                ->where('user_id', $user->id)
                ->where('created_at', '>', $obj->updated_at)
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
            $workflowAlcada = WorkflowAlcada::where('workflow_alcadas.workflow_tipo_id', 1)// Tipo = Aprovação de OC
            ->where('ordem', ($workflowUsuario->ordem - 1))
                ->first();

            $usuariosAlcadaAnterior = $workflowAlcada->workflowUsuarios()->count();

            $aprovacoesAlcadaAnterior = $obj->aprovacoes()
                ->where('workflow_alcada_id', $workflowAlcada->id)
                ->where('created_at', '>=', $obj->updated_at)
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

    public static function verificaAprovaGrupo($tipo, $ids, User $user)
    {
        $aprovador = false;
        // Verifica se o usuário atual é um aprovador de alguma alçada
        $workflowUsuario = WorkflowUsuario::select(['workflow_usuarios.*', 'workflow_alcadas.ordem'])
            ->join('workflow_alcadas', 'workflow_alcadas.id', '=', 'workflow_usuarios.workflow_alcada_id')
            ->where('workflow_alcadas.workflow_tipo_id', 1)// Tipo = Aprovação de OC
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


        eval('$total_itens_a_aprovar = \\App\Models\\' . $tipo . '::where(\''. $tabela .'.id\', \'>\', 0);');
        $total_itens_a_aprovar->whereIn($tabela.'.id', $ids);
        $total_a_aprovar = $total_itens_a_aprovar->count();

        if ($total_a_aprovar) {

            // Verifica se o mesmo já aprovou todas os itens
            eval('$total_itens_aprovados_reprovados = \\App\Models\\' . $tipo . '::where(\''. $tabela .'.id\', \'>\', 0);');
            $total_itens_aprovados_reprovados->join('workflow_aprovacoes',function ($join) use($tipo_txt, $tabela){
                $join->on('workflow_aprovacoes.aprovavel_type','=',DB::raw("'".$tipo_txt ."'") );
                $join->on('workflow_aprovacoes.aprovavel_id','=',$tabela.'.id');
            })
                ->where('workflow_aprovacoes.user_id',$user->id)
                ->where('workflow_aprovacoes.created_at','>=',DB::raw($tabela .".updated_at"))
                ->whereIn($tabela.'.id', $ids);
            $total_aprovados_reprovados_pelo_user = $total_itens_aprovados_reprovados->count();

            if ($total_aprovados_reprovados_pelo_user == $total_a_aprovar) {
                return [
                    'podeAprovar' => true,
                    'iraAprovar' => false,
                    'jaAprovou' => true,
                    'aprovacao' => ($total_a_aprovar == $total_itens_aprovados_reprovados->where('workflow_aprovacoes.aprovado',1)->count()),
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
            $workflowAlcada = WorkflowAlcada::where('workflow_alcadas.workflow_tipo_id', 1)// Tipo = Aprovação de OC
            ->where('ordem', ($workflowUsuario->ordem - 1))
                ->first();

            $usuariosAlcadaAnterior = $workflowAlcada->workflowUsuarios()->count();

            eval('$total_itens_aprovados_reprovados_alc_anterior = \\App\Models\\' . $tipo . '::where(\''. $tabela .'.id\', \'>\', 0);');
            $total_itens_aprovados_reprovados_alc_anterior->join('workflow_aprovacoes',function ($join) use($tipo_txt, $tabela){
                $join->on('workflow_aprovacoes.aprovavel_type','=',DB::raw("'".$tipo_txt ."'") );
                $join->on('workflow_aprovacoes.aprovavel_id','=',$tabela.'.id');
            })
                ->where('workflow_aprovacoes.workflow_alcada_id',$workflowAlcada->id)
                ->where('workflow_aprovacoes.created_at','>=',DB::raw($tabela .".updated_at"))
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

        if (!$obj) {
            return false;
        }
        
        $podeAprovar = self::verificaAprovacoes($tipo, $id, $user);
        if (!$podeAprovar['iraAprovar']) {
            return false;
        }
        

        $workflowUsuario = WorkflowUsuario::select(['workflow_usuarios.*', 'workflow_alcadas.ordem'])
            ->join('workflow_alcadas', 'workflow_alcadas.id', '=', 'workflow_usuarios.workflow_alcada_id')
            ->where('workflow_alcadas.workflow_tipo_id', 1)// Tipo = Aprovação de OC
            ->where('user_id', $user->id)
            ->first();

        $workflowAprovacao = new WorkflowAprovacao([
            'workflow_alcada_id' => $workflowUsuario->workflow_alcada_id,
            'user_id' => $user->id,
            'aprovado'=>$resposta,
            'workflow_reprovacao_motivo_id' => intval($motivo_id)?$motivo_id:null,
            'justificativa' => strlen($justificativa)?$justificativa:null
        ]);

        $salvo = $obj->aprovacoes()->save($workflowAprovacao);

        return $salvo;
        
    }
}
