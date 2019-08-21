<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class UsuarioModel extends Model
{
    public static function rol_aplicacion()
    {
    	$datos = DB::select('select ur.id_usuario_rol,ur.id_aplicacion_usuario,ur.id_rol,ur.id_empresa,ur.objeto_permitido,ur.fecha_ini,ur.fecha_fin,au.id_aplicacion,au.username,ra.nombre,au.estado_sesion,au.estado_validacion,case when au.estado_sesion = 1 then \'ACTIVO\' else \'INACTIVO\' end estado from seguridadapp.usuario_rol ur inner join seguridadapp.aplicacion_usuario au on au.id_aplicacion_usuario = ur.id_aplicacion_usuario inner join seguridadapp.rol_aplicacion ra on ra.id_rol = ur.id_rol where au.id_aplicacion = 4 and au.estado_sesion = 1');                               

    	$client = new CLient([
            'base_uri' => 'http://10.0.0.14:1337/datos_maestros/'
            ]);
        $response = $client->request('GET','proyectos?estado=A');
        $proyectos = json_decode($response->getBody( )->getContents());
    	
        foreach($datos as $dat){
        	if($dat->objeto_permitido != ''){
        		$array_obj = explode(';', $dat->objeto_permitido);
        		$obj_permitido = '';
        		foreach ($array_obj as $rl) {
        			foreach($proyectos as $proy){
	        			if($proy->id_proyecto == $rl){
	        				if($proy->cod_empresa == '0004'){
				                $proy->cod_empresa = 'DVC';
				            }else if($proy->cod_empresa == '0006'){
				                $proy->cod_empresa = 'FE';
				            }else if($proy->cod_empresa == '0010'){
				                $proy->cod_empresa = 'FA';
				            }else if($proy->cod_empresa == '0018'){
				                $proy->cod_empresa = 'FP';
				            }else if($proy->cod_empresa == '0019'){
				                $proy->cod_empresa = 'FAI';
				            }else if($proy->cod_empresa == '0020'){
				                $proy->cod_empresa = 'FT';
				            }
				            $obj_permitido .= $proy->cod_empresa .' - '. $proy->nombre_proyecto.';';
	        			}
        			}
	        	}
	        	$dat->objeto_permitido = $obj_permitido;
        	}else{
    			$dat->objeto_permitido = 'TODOS';
    		}
	            
	        
        	
        }
        //print(json_encode($datos));
    	return $datos;
    }
}
