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
            'dashboard.access' => 'Acesso ao Painel',
            'users.list' => 'Listagem de Usuários',
            'users.create' => 'Criação de Usuários',
            'users.edit'   => 'Edição de Usuários',
            'users.view'   => 'Visualização de Usuários',
            'users.deactivate'  => 'Inativação de Usuários',
            'roles.list' => 'Listagem de Papéis',
            'roles.create' => 'Criação de Papéis',
            'roles.edit'   => 'Edição de Papéis',
            'roles.view'   => 'Visualização de Papéis',
            'permissions.list' => 'Listagem de Permissões',
            'permissions.create' => 'Criação de Permissões',
            'permissions.edit'   => 'Edição de Permissões',
            'permissions.view'   => 'Visualização de Permissões',
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
    }
}
