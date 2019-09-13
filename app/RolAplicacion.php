<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\UserRol;

class RolAplicacion extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $connection = 'pgsql';
   protected $table = 'seguridadapp.rol_aplicacion';
   protected $primaryKey = 'id_rol';
   public $timestamps = false;
}
