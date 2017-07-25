<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MemoriaCalculo
 * @package App\Models
 * @version June 29, 2017, 4:47 pm BRT
 */
class MemoriaCalculo extends Model
{

    public $table = 'memoria_calculos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'nome',
        'padrao',
        'user_id',
        'modo',
        'obra_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'user_id' => 'integer',
        'modo' => 'string',
        'obra_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'required',
        'modo' => 'required',
        'estrutura_bloco' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function blocos()
    {
        return $this->hasMany(MemoriaCalculoBloco::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }

    public function blocosEstruturados($possibilidadeEdicao = null){
        $blocos = [];
        $memoriaBlocos = $this->blocos()
            ->orderBy('ordem_bloco','ASC')
            ->orderBy('ordem_linha','ASC')
            ->orderBy('ordem','ASC')
            ->with('estruturaObj','pavimentoObj','trechoObj','mcMedicaoPrevisoes')
            ->get();
        if(count($memoriaBlocos)){
            $estruturas = [];
            $pavimentos = [];
            $trechos = [];
            foreach ($memoriaBlocos as $memoriaBloco) {
                if(is_null($possibilidadeEdicao)){
                    $editavel = !count($memoriaBloco->mcMedicaoPrevisoes);
                }
                if($possibilidadeEdicao===true){
                    $editavel = true;
                }
                if($possibilidadeEdicao===false){
                    $editavel = false;
                }
                if(!isset($estruturas[$memoriaBloco->estrutura])){
                    $estruturas[$memoriaBloco->estrutura] = [
                        'id'=>   $memoriaBloco->ordem_bloco,
                        'objId'=>   $memoriaBloco->estrutura,
                        'nome'=> $memoriaBloco->estruturaObj->nome,
                        'largura'=> $memoriaBloco->estruturaObj->largura_visual,
                        'ordem' => $memoriaBloco->ordem_bloco,
                        'itens' => [],
                        'editavel'=>$editavel
                    ];
                }else{
                    if(!$editavel){
                        $estruturas[$memoriaBloco->estrutura]['editavel'] = $editavel;
                    }
                }

                if(!isset($pavimentos[$memoriaBloco->estrutura][$memoriaBloco->pavimento])){
                    $countEstrutura = !isset($pavimentos[$memoriaBloco->estrutura])?1:count($pavimentos[$memoriaBloco->estrutura])+1;
                    $pavimentos[$memoriaBloco->estrutura][$memoriaBloco->pavimento] = [
                        'id'=>   $countEstrutura,
                        'objId'=>   $memoriaBloco->pavimento,
                        'nome'=> $memoriaBloco->pavimentoObj->nome,
                        'largura'=> $memoriaBloco->pavimentoObj->largura_visual,
                        'ordem' => $memoriaBloco->ordem_linha,
                        'estrutura' => $memoriaBloco->estrutura,
                        'itens' => [],
                        'editavel'=>$editavel
                    ];
                }else{
                    if(!$editavel){
                        $pavimentos[$memoriaBloco->estrutura][$memoriaBloco->pavimento]['editavel'] = $editavel;
                    }
                }

                if(!isset($trechos[$memoriaBloco->estrutura][$memoriaBloco->pavimento][$memoriaBloco->id])){
                    $countTrecho = !isset($trechos[$memoriaBloco->estrutura][$memoriaBloco->pavimento])?1:count($trechos[$memoriaBloco->estrutura][$memoriaBloco->pavimento])+1;
                    $trechos[$memoriaBloco->estrutura][$memoriaBloco->pavimento][$memoriaBloco->id] = [
                        'id'=>   $countTrecho,
                        'blocoId'=>   $memoriaBloco->id,
                        'objId'=>   $memoriaBloco->trecho,
                        'nome'=> $memoriaBloco->trechoObj->nome,
                        'largura'=> $memoriaBloco->trechoObj->largura_visual,
                        'ordem' => $memoriaBloco->ordem,
                        'estrutura' => $memoriaBloco->estrutura,
                        'pavimento' => $memoriaBloco->pavimento,
                        'editavel'=>$editavel
                    ];
                }else{
                    if(!$editavel){
                        $trechos[$memoriaBloco->estrutura][$memoriaBloco->pavimento][$memoriaBloco->id]['editavel'] = $editavel;
                    }
                }

            }
            // organiza a array
            foreach ($trechos as $estrutura_id => $estruturaTrechos){
                foreach ($estruturaTrechos as $pavimento_id => $pavimentoTrechos) {
                    foreach ($pavimentoTrechos as $trecho) {
                        $pavimentos[$trecho['estrutura']][$trecho['pavimento']]['itens'][] = $trecho;
                    }
                }

            }

            foreach ($pavimentos as $estrutura_id => $pavimentos_internos){
                foreach ($pavimentos_internos as $pavimento_interno){
                    $estruturas[$pavimento_interno['estrutura']]['itens'][] = $pavimento_interno;
                }
            }

            foreach ($estruturas as $estrutura){
                $blocos[$estrutura['ordem']] = $estrutura;
            }

        }
        ksort($blocos);
        
        return $blocos;
    }
}
