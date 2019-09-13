<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Empresa;
use Illuminate\Foundation\Auth\User as Authenticatable;


class UserRol extends Authenticatable
{
	//use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


	protected $connection = 'pgsql';
   protected $table = 'seguridadapp.usuario_rol';
   protected $primaryKey = 'id_usuario_rol';
   public $timestamps = false;

   public function user(){
   	return $this->belongsTo(User::class,'id_aplicacion_usuario');
   }

    public function rolaplicacion(){
      return $this->belongsTo(RolAplicacion::class,'id_rol');
    }

}