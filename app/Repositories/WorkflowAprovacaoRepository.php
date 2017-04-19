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
        eval('$workflow_tipo_id= \\App\Models\\' . $tipo . '::$workflow_tipo_id;');

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
            $workflowAlcada = WorkflowAlcada::where('workflow_alcadas.workflow_tipo_id', $workflow_tipo_id)// Tipo = Aprovação de OC
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
        eval('$workflow_tipo_id= \\App\Models\\' . $tipo . '::$workflow_tipo_id;');
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

            // Verifica se o mesmo já aprovou todas os itens
            $total_aprovados_reprovados = self::verificaTotalJaAprovadoReprovado($tipo, $ids, $user);
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
        eval('$workflow_tipo_id= \\App\Models\\' . $tipo . '::$workflow_tipo_id;');

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

        $workflowAprovacao = new WorkflowAprovacao([
            'workflow_alcada_id' => $workflowUsuario->workflow_alcada_id,
            'user_id' => $user->id,
            'aprovado'=>$resposta,
            'workflow_reprovacao_motivo_id' => intval($motivo_id)?$motivo_id:null,
            'justificativa' => strlen($justificativa)?$justificativa:null
        ]);

        $salvo = $obj->aprovacoes()->save($workflowAprovacao);
        
        // Verifica se é a primeira aprovação deste item dentre os irmãos
        $ids = $obj->irmaosIds();
        $total_ja_votado_geral = self::verificaTotalJaAprovadoReprovado($tipo,$ids);
        $total_ja_votado = self::verificaTotalJaAprovadoReprovado($tipo,$ids,null, $obj->id);
        if($total_ja_votado_geral['total_avaliado']===1){
            // Se for já altera o status do pai para Em Aprovação
            $obj->paiEmAprovacao();
        }

        // Se não for, verifica se já é a última
        $qtd_aprovadores = self::verificaQuantidadeUsuariosAprovadores($workflow_tipo_id);

        if($qtd_aprovadores){
            // Divide a qtd de aprovações/reprovações pela quantidade de aprovadores
            $avaliacoes = $total_ja_votado['total_avaliado']/$qtd_aprovadores;
            if($avaliacoes===1){
                // Se for já salva se foi aprovado ou reprovado
                $obj->timestamps = false;
                $obj->aprovado = ($total_ja_votado['total_aprovado']===$total_ja_votado['total_avaliado']);
                $obj->save();
                // Chama função do model do item que irá verificar batendo no pai se todos os filhos foram aprovados
                $obj->confereAprovacaoGeral();
            }
        }



        return $salvo;
        
    }
    
    private static function verificaTotalaAprovar($tipo, $ids){
        eval('$model= \\App\Models\\' . $tipo . '::firstOrNew([]);');
        $tabela = $model->getTable();


        eval('$total_itens_a_aprovar = \\App\Models\\' . $tipo . '::where(\''. $tabela .'.id\', \'>\', 0);');
        $total_itens_a_aprovar->whereIn($tabela.'.id', $ids);
        return $total_itens_a_aprovar->count();
    }

    private static function verificaTotalJaAprovadoReprovado($tipo, $ids, $user = null, $item_id = null){
        eval('$model= \\App\Models\\' . $tipo . '::firstOrNew([]);');
        $tabela = $model->getTable();

        $tipo_txt = 'App\\\\Models\\\\' . $tipo;

        eval('$total_itens_aprovados_reprovados = \\App\Models\\' . $tipo . '::where(\''. $tabela .'.id\', \'>\', 0);');
        $total_itens_aprovados_reprovados->join('workflow_aprovacoes',function ($join) use($tipo_txt, $tabela){
            $join->on('workflow_aprovacoes.aprovavel_type','=',DB::raw("'".$tipo_txt ."'") );
            $join->on('workflow_aprovacoes.aprovavel_id','=',$tabela.'.id');
        })
            ->where('workflow_aprovacoes.created_at','>=',DB::raw($tabela .".updated_at"))
            ->whereIn($tabela.'.id', $ids);
        if($user){
            $total_itens_aprovados_reprovados->where('workflow_aprovacoes.user_id',$user->id);
        }
        if($item_id){
            $total_itens_aprovados_reprovados->where('workflow_aprovacoes.aprovavel_id',$item_id);
        }
        return [
            'total_avaliado' => $total_itens_aprovados_reprovados->count(),
            'total_aprovado' => $total_itens_aprovados_reprovados->where('workflow_aprovacoes.aprovado',1)->count()
        ];
    }

    private static function verificaQuantidadeUsuariosAprovadores($workflow_tipo_id){
        $qtd_usuarios = 0;
        $workflow_alcadas = WorkflowAlcada::where('workflow_tipo_id',$workflow_tipo_id)->get();
        foreach ($workflow_alcadas as $alcada){
            $qtd_usuarios += $alcada->workflowUsuarios()->count();
        }
        
        return $qtd_usuarios;
    }
}
