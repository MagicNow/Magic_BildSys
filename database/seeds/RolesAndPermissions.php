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
        Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\DB::table('permission_role')->delete();
        \Illuminate\Support\Facades\DB::table('permission_role')->truncate();
        \Illuminate\Support\Facades\DB::table('permission_user')->delete();
        \Illuminate\Support\Facades\DB::table('permission_user')->truncate();
        \Illuminate\Support\Facades\DB::table('roles')->delete();
        \Illuminate\Support\Facades\DB::table('roles')->truncate();
        \Illuminate\Support\Facades\DB::table('permissions')->delete();
        \Illuminate\Support\Facades\DB::table('permissions')->truncate();
        $cargos = [
            'Administrador',
            'Suprimentos',
            'Fornecedor',
            'Planejamento'
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

            'obras.list' => 'Listagem de obras',
            'obras.create' => 'Criação de obras',
            'obras.edit'   => 'Edição de obras',
            'obras.view'   => 'Visualização de obras',

            'insumos.list' => 'Listagem de insumos',
            'insumos.view'   => 'Visualização de insumos',
            'insumos.availability'   => 'Alterar disponibilidade de insumos',

            'template_planilhas.list' => 'Listagem de templates de planilha',

            'grupos_insumos.list' => 'Listagem de grupos de insumos',
            'grupos_insumos.view'   => 'Visualização de grupos de insumos',
            'grupos_insumos.availability'   => 'Alterar disponibilidade de grupos de insumo',

            'fornecedores.list'   => 'Listagem de fornecedores',
            'fornecedores.create'   => 'Criação de fornecedores',
            'fornecedores.edit'   => 'Edição de fornecedores',
            'fornecedores.delete'   => 'Remoção de fornecedores',

            'compradorInsumos.list'   => 'Listagem de comprador/insumos',
            'compradorInsumos.create'   => 'Criação de comprador/insumos',
            'compradorInsumos.delete'   => 'Remoção de comprador/insumos',
            'compradorInsumos.deleteBlocoView'   => 'Remoção em bloco de comprador/insumos',

            'solicitacaoInsumos.list'   => 'Listagem de solicitação insumos',
            'solicitacaoInsumos.create'   => 'Criação de solicitação insumo',
            'solicitacaoInsumos.edit'   => 'Edição de solicitação insumo',
            'solicitacaoInsumos.delete'   => 'Remoção de solicitação insumo',

            'contratoTemplates.list'   => 'Listagem de Templates de Contratos',
            'contratoTemplates.create'   => 'Criação de Template de Contrato',
            'contratoTemplates.edit'   => 'Edição de Template de Contrato',
            'contratoTemplates.delete'   => 'Remoção de Template de Contrato',

            'configuracaoEstaticas.list'   => 'Listagem de Configuração padrão',
            'configuracaoEstaticas.edit'   => 'Edição de Configuração padrão',

            ################# SITE ###################
            'compras_lembretes.list' => 'Listagem de compras e lembretes',

            'compras.geral' => 'Acesso aos módulos de compras',

            'ordens_de_compra.list' => 'Listagem de ordens de compra',
            'ordens_de_compra.detalhes' => 'Detalhes de ordens de compra',
            'ordens_de_compra.detalhes_servicos' => 'Detalhes de serviços das ordens de compra',

            'site.dashboard' => 'Dashboard do site',

            'retroalimentacao.list' => 'Listagem de retroaliamentação',
            'retroalimentacao.create' => 'Criação de retroaliamentação',

            'quadroDeConcorrencias.list' => 'Listagem de Quadro De Concorrências',
            'quadroDeConcorrencias.create' => 'Criação de Quadro De Concorrência',
            'quadroDeConcorrencias.edit' => 'Edição de Quadro De Concorrência',
            'quadroDeConcorrencias.view' => 'Visualização de Quadro De Concorrência',
            'quadroDeConcorrencias.informar_valor' => 'Informar valores em quadros de concorrência',
            'quadroDeConcorrencias.delete'   => 'Remoção Física de Quadro De Concorrência',

            'catalogo_acordos.list' => 'Listagem de Catálogo Acordos',
            'catalogo_acordos.create' => 'Criação de Catálogo Acordos',
            'catalogo_acordos.edit'   => 'Edição de Catálogo Acordos',
            'catalogo_acordos.view'   => 'Visualização de Catálogo Acordos',

            'equalizacao_tecnicas.list'   => 'Listagem de equalização tecnica',
            'equalizacao_tecnicas.create'   => 'Criação de equalização tecnica',
            'equalizacao_tecnicas.edit'   => 'Edição de equalização tecnica',
            'equalizacao_tecnicas.delete'   => 'Remoção de equalização tecnica',

            'contratos.list'        => 'Listagem de contratos',
            'contratos.show'        => 'Visualização de contrato',
            'contratos.distratar'   => 'Distratar item do contrato',
            'contratos.reajustar'   => 'Reajustar item do contrato',
            'contratos.reapropriar' => 'Reapropriar item do contrato',

        ];

        $permissionAccess = [];
        foreach ($permissions as $permission => $permissionDesc) {
            $permissionAccess[] = Defender::createPermission($permission , $permissionDesc);
        }

        $users = User::where('id', '>', 1)->get();
        foreach ($users as $user) {
            $user->attachPermission(Defender::findPermission('site.dashboard'));
        }

        // Adiciona permissões no SuperUser e no Planejamento
        foreach ($permissionAccess as $permission)
        {
            $roleSuperuser->attachPermission($permission);
            $roles[3]->attachPermission($permission);
        }

        // Permissões para Suprimentos
        $roles[1]->attachPermission(Defender::findPermission('site.dashboard'));
        $roles[1]->attachPermission(Defender::findPermission('retroalimentacao.create'));
        $roles[1]->attachPermission(Defender::findPermission('quadroDeConcorrencias.list'));
        $roles[1]->attachPermission(Defender::findPermission('quadroDeConcorrencias.create'));
        $roles[1]->attachPermission(Defender::findPermission('quadroDeConcorrencias.edit'));
        $roles[1]->attachPermission(Defender::findPermission('quadroDeConcorrencias.view'));
        $roles[1]->attachPermission(Defender::findPermission('quadroDeConcorrencias.informar_valor'));
        $roles[1]->attachPermission(Defender::findPermission('catalogo_acordos.list'));
        $roles[1]->attachPermission(Defender::findPermission('catalogo_acordos.create'));
        $roles[1]->attachPermission(Defender::findPermission('catalogo_acordos.edit'));
        $roles[1]->attachPermission(Defender::findPermission('catalogo_acordos.view'));
        $roles[1]->attachPermission(Defender::findPermission('equalizacao_tecnicas.list'));
        $roles[1]->attachPermission(Defender::findPermission('equalizacao_tecnicas.create'));
        $roles[1]->attachPermission(Defender::findPermission('equalizacao_tecnicas.edit'));
        $roles[1]->attachPermission(Defender::findPermission('equalizacao_tecnicas.delete'));
        $roles[1]->attachPermission(Defender::findPermission('fornecedores.create'));
        $roles[1]->attachPermission(Defender::findPermission('fornecedores.list'));
        $roles[1]->attachPermission(Defender::findPermission('compras.geral'));
        $roles[1]->attachPermission(Defender::findPermission('compras_lembretes.list'));
        $roles[1]->attachPermission(Defender::findPermission('ordens_de_compra.list'));
        $roles[1]->attachPermission(Defender::findPermission('ordens_de_compra.detalhes'));
        $roles[1]->attachPermission(Defender::findPermission('ordens_de_compra.detalhes_servicos'));
        $roles[1]->attachPermission(Defender::findPermission('dashboard.access'));
        $roles[1]->attachPermission(Defender::findPermission('lembretes.list'));
        $roles[1]->attachPermission(Defender::findPermission('lembretes.edit'));
        $roles[1]->attachPermission(Defender::findPermission('compradorInsumos.list'));
        $roles[1]->attachPermission(Defender::findPermission('compradorInsumos.create'));
        $roles[1]->attachPermission(Defender::findPermission('obras.list'));
        $roles[1]->attachPermission(Defender::findPermission('fornecedores.list'));

        // Permissões para Fornecedor
        $roles[2]->attachPermission(Defender::findPermission('quadroDeConcorrencias.informar_valor'));
        $roles[2]->attachPermission(Defender::findPermission('quadroDeConcorrencias.list'));
        $roles[2]->attachPermission(Defender::findPermission('site.dashboard'));

        Schema::enableForeignKeyConstraints();
    }
}
