<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeederRolesAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $UserTableSeeder = new UserTableSeeder();
        $UserTableSeeder->run();

        $rolesSeeder = new RolesAndPermissions();
        $rolesSeeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (app()->env != 'testing')
            DB::select('SET FOREIGN_KEY_CHECKS = 0');

        DB::table('role_user')->delete();
        DB::table('permission_role')->delete();
        DB::table('permission_user')->delete();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();

        if (app()->env != 'testing')
            DB::select('SET FOREIGN_KEY_CHECKS = 1');
    }
}
