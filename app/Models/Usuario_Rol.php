<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
class Usuario_Rol extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'seguridadapp.usuario_rol';
    protected $primaryKey = 'id_usuario_rol';
    public $timestamps = false;
    protected $fillable = ['id_aplicacion_usuario','id_rol','empresa','objeto_permitido','fecha_ini','fecha_fin'];
    public static function usuario(){
        //$usuarios = DB::table('aplicacion_usuario')->join('usuario_rol','id_aplicacion_usuario','=','id_aplicacion_usuario')
        //return Usuario_Seguridad::where('id_unidad_negocio','=',$id)->get();
    }
}
