<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class RolesAndPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('permission_role')->delete();
        \Illuminate\Support\Facades\DB::table('permission_user')->delete();
        \Illuminate\Support\Facades\DB::table('roles')->delete();
        \Illuminate\Support\Facades\DB::table('permissions')->delete();
        $cargos = [
            'Administrador',
            'Fornecedor'
        ];

        $roles = [];

        foreach ($cargos as $cargo) {
            $roles[] = Defender::createRole($cargo);
        }

        $permissionScope = [];

        //Add Administrador role
        $roleSuperuser = $roles[0];
        $user = User::find(1);
        $user->attachRole($roleSuperuser);

        foreach ($permissionScope as $permission) {
            $roleSuperuser->attachPermission($permission);
        }

        $permissions = [
            //ADMIN
            'dashboard.access' => 'Acesso ao painel',

            'users.list' => 'Listagem de usuários',
            'users.create' => 'Criação de usuários',
            'users.edit'   => 'Edição de usuários',
            'users.view'   => 'Visualização de usuários',
            'users.deactivate'  => 'Inativação de usuários',

            'roles.list' => 'Listagem de papéis',
            'roles.create' => 'Criação de papéis',
            'roles.edit'   => 'Edição de papéis',
            'roles.view'   => 'Visualização de papéis',

            'permissions.list' => 'Listagem de permissões',
            'permissions.create' => 'Criação de permissões',
            'permissions.edit'   => 'Edição de permissões',
            'permissions.view'   => 'Visualização de permissões',

            'orcamentos.list' => 'Listagem de orçamentos',
            'orcamentos.import' => 'Importar orçamentos',

            'cronograma_por_obras.list' => 'Listagem de cronograma por obras',

            'cronograma_de_obras.list' => 'Listagem de cronograma de obras',
            'cronograma_de_obras.edit'   => 'Edição de cronograma de obras',
            'cronograma_de_obras.view'   => 'Visualização de cronograma de obras',

            'planejamento.import' => 'Importar planejamentos',

            'lembretes.list' => 'Listagem de lembretes',
            'lembretes.create' => 'Criação de lembretes',
            'lembretes.edit'   => 'Edição de lembretes',
            'lembretes.view'   => 'Visualização de lembretes',

            'alcadas.list' => 'Listagem de alçadas',
            'alcadas.create' => 'Criação de alçadas',
            'alcadas.edit'   => 'Edição de alçadas',
            'alcadas.view'   => 'Visualização de alçadas',

            'motivos_reprovacao.list' => 'Listagem de motivos de reprovação',
            'motivos_reprovacao.create' => 'Criação de motivos de reprovação',
            'motivos_reprovacao.edit'   => 'Edição de motivos de reprovação',
            'motivos_reprovacao.view'   => 'Visualização de motivos de reprovação',

            'contratos.list' => 'Listagem de contratos',
            'contratos.create' => 'Criação de contratos',
            'contratos.edit'   => 'Edição de contratos',
            'contratos.view'   => 'Visualização de contratos',

            'obras.list' => 'Listagem de obras',
            'obras.create' => 'Criação de obras',
            'obras.edit'   => 'Edição de obras',
            'obras.view'   => 'Visualização de obras',

            'insumos.list' => 'Listagem de insumos',
            'insumos.view'   => 'Visualização de insumos',

            'template_planilhas.list' => 'Listagem de templates de planilha',

            'grupos_insumos.list' => 'Listagem de grupos de insumos',
            'grupos_insumos.view'   => 'Visualização de grupos de insumos',

            //SITE
            'compras_lembretes.list' => 'Listagem de compras e lembretes',

            'compras.geral' => 'Acesso aos módulos de compras',

            'ordens_de_compra.list' => 'Listagem de ordens de compra',
            'ordens_de_compra.detalhes' => 'Detalhes de ordens de compra',
            'ordens_de_compra.detalhes_servicos' => 'Detalhes de serviços das ordens de compra',

            'site.dashboard' => 'Dashboard do site',

            'retroalimentacao.create' => 'Criação de retroaliamentação',

            'quadroDeConcorrencias.list' => 'Listagem de Quadro De Concorrências',
            'quadroDeConcorrencias.create' => 'Criação de Quadro De Concorrência',
            'quadroDeConcorrencias.edit' => 'Edição de Quadro De Concorrência',
            'quadroDeConcorrencias.view' => 'Visualização de Quadro De Concorrência',
            'quadroDeConcorrencias.informar_valor' => 'Informar valores em quadros de concorrência',
            'quadroDeConcorrencias.delete'   => 'Remoção Física de Quadro De Concorrência',
        ];

        $permissionAccess = [];
        foreach ($permissions as $permission => $permissionDesc) {
            $permissionAccess[] = Defender::createPermission($permission , $permissionDesc);
        }

        $users = User::where('id', '>', 1)->get();
        foreach ($users as $user) {
            $user->attachPermission($permissionAccess[0]);
        }

        foreach ($permissionAccess as $permission)
        {
            $roleSuperuser->attachPermission($permission);
        }

//        $roles[1]->attachPermission(Defender::findPermission('quadroDeConcorrencias.informar_valores'));
//        $roles[1]->attachPermission(Defender::findPermission('quadroDeConcorrencias.list'));
//        $roles[1]->attachPermission(Defender::findPermission('site.dashboard'));
    }
}
