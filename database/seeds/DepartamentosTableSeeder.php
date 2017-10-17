<?php

use Illuminate\Database\Seeder;

class DepartamentosTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('departamentos')->delete();

    DB::table('departamentos')->insert([
      'nome' => 'Novos negócios'
    ]);

    DB::table('departamentos')->insert([
      'nome' => 'GETEC'
    ]);

    DB::table('departamentos')->insert([
      'nome' => 'Imcorporação/Produto'
    ]);

    DB::table('departamentos')->insert([
      'nome' => 'Studio Bild'
    ]);

    DB::table('departamentos')->insert([
      'nome' => 'Marketing'
    ]);
 
    DB::table('departamentos')->insert([
      'nome' => 'Legalização'
    ]);
  }
}
