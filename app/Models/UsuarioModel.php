<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class UsuarioModel extends Model
{
    public static function rol_aplicacion()
    {
    	$datos = DB::select('select * from abastecimiento.v_listado_usuarios where id_aplicacion = 4 and estado_sesion = 1');                               

    	$proyectos = DB::connection('pgsqlProye')->select('select * from ggo.ggo_proyecto');
        foreach($datos as $dat){
        	if($dat->objeto_permitido != ''){
        		$array_obj = explode(';', $dat->objeto_permitido);
        		$obj_permitido = '';
        		foreach ($array_obj as $rl) {
        			foreach($proyectos as $proy){
	        			if(trim($proy->cod_proyecto) == $rl){
	        				if($proy->id_unidad_negocio == '0004'){
				                $proy->id_unidad_negocio = 'DVC';
				            }else if($proy->id_unidad_negocio == '0006'){
				                $proy->id_unidad_negocio = 'FE';
				            }else if($proy->id_unidad_negocio == '0010'){
				                $proy->id_unidad_negocio = 'FA';
				            }else if($proy->id_unidad_negocio == '0018'){
				                $proy->id_unidad_negocio = 'FP';
				            }else if($proy->id_unidad_negocio == '0019'){
				                $proy->id_unidad_negocio = 'FAI';
				            }else if($proy->id_unidad_negocio == '0020'){
				                $proy->id_unidad_negocio = 'FT';
				            }
				            $obj_permitido .= $proy->id_unidad_negocio .' - '. $proy->nombre_proyecto.';';
	        			}
        			}
	        	}
	        	$dat->objeto_permitido = $obj_permitido;
        	}else{
    			$dat->objeto_permitido = 'TODOS';
    		}
        }
    	return $datos;
    }
}
