<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarteirasSla extends Model
{
    public $table = 'carteiras_sla';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];
}
