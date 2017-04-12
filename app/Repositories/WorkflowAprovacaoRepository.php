<?php

namespace App\Repositories;


use App\Models\User;
use App\Models\WorkflowAlcada;
use App\Models\WorkflowAprovacao;
use App\Models\WorkflowUsuario;
use Illuminate\Support\Facades\Auth;

class WorkflowAprovacaoRepository
{
    public static function verificaAprovacoes($tipo, $id, User $user)
    {
        $aprovador = false;
        // Verifica se o usuário atual é um aprovador de alguma alçada
        $workflowUsuario = WorkflowUsuario::select(['workflow_usuarios.*', 'workflow_alcadas.ordem'])
            ->join('workflow_alcadas', 'workflow_alcadas.id', '=', 'workflow_usuarios.workflow_alcada_id')
            ->where('workflow_alcadas.workflow_tipo_id', 1)// Tipo = Aprovação de OC
            ->where('user_id', Auth::id())
            ->first();
        if (!$workflowUsuario) {
            // Já vaza
            return [
                'podeAprovar'=>false
            ];
        }

        eval('$obj = ' . $tipo . '::find(' . $id . ');');
        if ($obj) {

            // Verifica se a já é a alçada atual que o usuário está já aprovou
            $jaAprovou = $obj->aprovacoes()
                ->where('user_id',$user->id)
                ->where('created_at','>', $obj->updated_at)
                ->first();
            if($jaAprovou){
                return [
                    'podeAprovar'=>true,
                    'iraAprovar'=>false,
                    'jaAprovou'=>true,
                    'aprovacao'=>$jaAprovou->aprovado,
                    'msg'=>null
                ];
            }

            // Verifica se a alçada dele é a primeira
            if ($workflowUsuario->ordem === 1) {
                return [
                    'podeAprovar'=>true,
                    'iraAprovar'=>true,
                    'jaAprovou'=>false,
                    'aprovacao'=>null,
                    'msg'=>null
                ];
            }
            // Caso não é a primeira, verifica as aprovações da alçada anterior
            $workflowAlcada = WorkflowAlcada::where('workflow_alcadas.workflow_tipo_id', 1)// Tipo = Aprovação de OC
                ->where('ordem', ($workflowUsuario->ordem-1) )
                ->first();

            $usuariosAlcadaAnterior = $workflowAlcada->workflowUsuarios()->count();

            $aprovacoesAlcadaAnterior = $obj->aprovacoes()
                ->where('workflow_alcada_id',$workflowAlcada->id)
                ->where('created_at','>', $obj->updated_at)
                ->count();

            // Se a quantidade de usuários é maior do que as aprovações / reprovações
            if($usuariosAlcadaAnterior > $aprovacoesAlcadaAnterior){
                return [
                    'podeAprovar'=>true,
                    'iraAprovar'=>false,
                    'jaAprovou'=>false,
                    'aprovacao'=>null,
                    'msg'=>'Ainda falta aprovações da alçada anterior para que você possa aprovar'
                ];
            }

            return [
                'podeAprovar'=>true,
                'iraAprovar'=>true,
                'jaAprovou'=>false,
                'aprovacao'=>null,
                'msg'=>null
            ];
        }

        return [
            'podeAprovar'=>false,
            'iraAprovar'=>false,
            'jaAprovou'=>false,
            'aprovacao'=>null,
            'msg'=>'Item não encontrado'
        ];
    }
}
