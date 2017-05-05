<?php

namespace App\Models;

use Artesaos\Defender\Traits\HasDefender;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasDefender;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    // Fields that you do NOT want to audit.
    protected $dontKeepAuditOf = ['password'];

    public $fillable = [
        'name',
        'email',
        'password',
        'active',
        'admin',
        'remember_token'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'active' => 'integer',
        'admin' => 'integer',
        'remember_token' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'password' => 'required',
    ];

    public static $filters = [
        'name-string' => 'Nome',
        'email-string' => 'E-mail',
        'active-boolean' => 'Ativo',
        'admin-boolean' => 'Administrador',
        'created_at-date' => 'Criado em',
        'updated_at-date' => 'Atualizado em',
        //        'valor-integer' => 'Valor'
        //        'user_id-foreign_key-User-name-id' => 'Usuário'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

}
