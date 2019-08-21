<?php
    
namespace App\Models;
    
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Aplicacion_Usuario extends Model{
	
    protected $connection = 'pgsql';
    protected $table = 'seguridadapp.aplicacion_usuario';
    protected $primaryKey = 'id_aplicacion_usuario';
    public $timestamps = false;
    protected $fillable = ['id_aplicacion','username','fecha_ini','fecha_fin'];
    public static function usuarios(){
        //$usuarios = DB::table('aplicacion_usuario')->join('usuario_rol','id_aplicacion_usuario','=','id_aplicacion_usuario')
        //return Usuario_Seguridad::where('id_unidad_negocio','=',$id)->get();
    }
}
