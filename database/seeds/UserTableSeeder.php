<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {

    public function run()
    {

        \App\Models\User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'admin'=>'1'
        ]);

    }

}
