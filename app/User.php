<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\UserRol;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $connection = 'pgsql';
    protected $table = 'seguridadapp.aplicacion_usuario';
    protected $primaryKey = 'id_aplicacion_usuario';
    public $timestamps = false;

    protected $fillable = [
       'name', 'username','provider','provider_id','id_aplicacion','fecha_ini','fecha_fin'
    ];
    protected $hidden = [
        'remember_token',
    ];

    public function rol(){
      return $this->hasMany(UserRol::class,'id_aplicacion_usuario');
    }
}
