<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\CorreoConfirmacion;
use App\Http\Controllers\Controller;
use App\Models\Aplicacion_Usuario;
use App\Models\Usuario_Rol;
use App\Models\UsuarioModel;
use Alert;
use DateTime;

class UsuarioController extends Controller
{
	public function usuariosListing(){
    	$table_usuario = UsuarioModel::rol_aplicacion();
        //print(json_encode($table_usuario));
        return view('usuario',compact('table_usuario'));
    }

    public function addNewUsuario(){
        $perfil = DB::select('select id_rol,upper(nombre) as nombre from seguridadapp.rol_aplicacion where id_aplicacion = 4');
        $client = new CLient([
            'base_uri' => 'http://10.0.0.14:1337/datos_maestros/'
        ]);
        $response = $client->request('GET','empresa');
        $empresa = json_decode($response->getBody( )->getContents());
        $response1 = $client->request('GET','unidades_negocio');
        $unidades_negocio = json_decode($response1->getBody( )->getContents());
        $response2 = $client->request('GET','proyectos?estado=A');
        $proyectos = json_decode($response2->getBody()->getContents());
        foreach($proyectos as $pry){
            if($pry->cod_empresa == '0004'){
                $pry->cod_empresa = 'DVC';
            }else if($pry->cod_empresa == '0006'){
                $pry->cod_empresa = 'FE';
            }else if($pry->cod_empresa == '0010'){
                $pry->cod_empresa = 'FA';
            }else if($pry->cod_empresa == '0018'){
                $pry->cod_empresa = 'FP';
            }else if($pry->cod_empresa == '0019'){
                $pry->cod_empresa = 'FAI';
            }else if($pry->cod_empresa == '0020'){
                $pry->cod_empresa = 'FT';
            }
        }
        $list_proy_exi = DB::select('select distinct(u.objeto_permitido) from seguridadapp.usuario_rol u inner join seguridadapp.aplicacion_usuario a on a.id_aplicacion_usuario = u.id_aplicacion_usuario where a.id_aplicacion = 4 and u.objeto_permitido is not null');
        $array_cod = '';
        if(!empty($list_proy_exi)){
            $codigos = '';               
            foreach($list_proy_exi as $list){
                $item_list = explode(";",$list->objeto_permitido);          
                foreach($item_list as $rl){
                    $codigos .= $rl.';';
                }
            }
            if ($codigos != '') {
                $array_cod = explode(";",substr($codigos,0,-1));
            }
        }
        foreach($array_cod as $cod){
            $en_uso = array_search($cod, array_column($proyectos, 'id_proyecto'));
            unset($proyectos[$en_uso]);
            $proyectos = array_values($proyectos);
        }
        return view('usuarios/addNew',compact('perfil','empresa','unidades_negocio','proyectos','array_cod'));
    }

    public function setEstado($id){
        $usuario_state = Aplicacion_Usuario::where('id_aplicacion_usuario',$id)->first();
        if($usuario_state->estado_sesion == 1){
            $inactivar = DB::select('update seguridadapp.aplicacion_usuario set estado_sesion = 0 where id_aplicacion_usuario ='.$id);
            Alert::success('El usuario fue desactivado exitosamente','DESHABILITADO');
            return redirect('/gestion_users');
        }elseif($usuario_state->estado_sesion == 0){
            $activar = DB::select('update seguridadapp.aplicacion_usuario set estado_sesion = 1 where id_aplicacion_usuario ='.$id);
            Alert::success('El usuario fue activado exitosamente','ACTIVADO');
            return redirect('/gestion_users');
        }
    }

    public function editOldUsuario($id){
        try {
            $perfil = DB::select('select id_rol,upper(nombre) as nombre from seguridadapp.rol_aplicacion where id_aplicacion = 4');
            $usuario_app = DB::select("select id_aplicacion_usuario, username, id_rol, id_empresa, objeto_permitido, id_unidad_negocio from seguridadapp.edit_user($id)");
            
            $client = new CLient([
                'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
            ]);
            $response = $client->request('GET','empresa');
            $empresa = json_decode($response->getBody( )->getContents());
            $response2 = $client->request('GET','directorio?userPrincipalName='.$usuario_app[0]->username);
            $usuario_directorio = json_decode($response2->getBody( )->getContents());
            $contenido_objeto = [];
            $sr_proyectos = '';
            $obj_permitido = '';


            if($usuario_app[0]->objeto_permitido != null){
                $objeto_permitido =  explode(';', $usuario_app[0]->objeto_permitido);
                foreach($objeto_permitido as $obj){
                    $response5 = $client->request('GET','proyectos?estado=A&&id_proyecto='.$obj);
                    $sr_proyectos = json_decode($response5->getBody( )->getContents());
                        foreach($sr_proyectos as $sr_proyectos){
                            if($sr_proyectos->cod_empresa == '0004'){
                                $sr_proyectos->cod_empresa = 'DVC';
                            }else if($sr_proyectos->cod_empresa == '0006'){
                                $sr_proyectos->cod_empresa = 'FE';
                            }else if($sr_proyectos->cod_empresa == '0010'){
                                $sr_proyectos->cod_empresa = 'FA';
                            }else if($sr_proyectos->cod_empresa == '0018'){
                                $sr_proyectos->cod_empresa = 'FP';
                            }else if($sr_proyectos->cod_empresa == '0019'){
                                $sr_proyectos->cod_empresa = 'FAI';
                            }else if($sr_proyectos->cod_empresa == '0020'){
                                $sr_proyectos->cod_empresa = 'FT';
                            }
                            $obj_permitido .= $sr_proyectos->cod_empresa .' - '. $sr_proyectos->nombre_proyecto.';';

                        }
                        $contenido_objeto[] = ($sr_proyectos);
                }
            }else{
                $obj_permitido = '0';
            }

            $response5 = $client->request('GET','proyectos?estado=A');
            $proyectos = json_decode($response5->getBody( )->getContents());
            foreach($proyectos as $pry){
                if($pry->cod_empresa == '0004'){
                    $pry->cod_empresa = 'DVC';
                }else if($pry->cod_empresa == '0006'){
                    $pry->cod_empresa = 'FE';
                }else if($pry->cod_empresa == '0010'){
                    $pry->cod_empresa = 'FA';
                }else if($pry->cod_empresa == '0018'){
                    $pry->cod_empresa = 'FP';
                }else if($pry->cod_empresa == '0019'){
                    $pry->cod_empresa = 'FAI';
                }else if($pry->cod_empresa == '0020'){
                    $pry->cod_empresa = 'FT';
                }
            }
           /*$contenido_objeto = array();
            foreach ($sr_proyectos as $rl) {
                $detalle = "";
                $detalle = (object) array(
                    'id_objeto'=>$rl->id_proyecto,
                    'descripcion'=>$rl->nombre_proyecto);
                array_push($contenido_objeto,$detalle);
            }*/
            //print(json_encode($contenido_objeto));
            if (!empty($usuario_directorio)) {
                return view('usuarios/editOld',compact('usuario_app','usuario_directorio','perfil','empresa','contenido_objeto','proyectos','obj_permitido'));
            }else {
                echo '<div class="text-center"><img height="250px" src="img/error.png" /><h5>Ocurrio un error</h5></div>';
            }
        } catch (Exception $e) {
            echo '<div class="text-center"><img height="250px" src="img/error.png" /><h5>Ocurrio un error</h5></div>';
        }
    }

    public function cargaUser($email){
        $client = new CLient([
        'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
        //'timeout' => 4.0,
        ]);
        $response2 = $client->request('GET','directorio?userPrincipalName='.$email);
        $usuario = json_decode($response2->getBody( )->getContents());
        return response()->json($usuario);
    }

    public function addUsuario(Request $request){
        $empresa = $request->idEmpresa;
        $rol = $request->selectPerfil;
        $correo = $request->inputEmail;
        $nombres = $request->inputNombres;
        $apellidos = $request->inputApellidos;
        $nombre = $nombres.' '.$apellidos;
        $radioEleccion = $request->optradio;

        $dia = new DateTime();
        $dia->format('d-m-y');
        
        $no_duplicate = DB::select("select * from seguridadapp.aplicacion_usuario where username = '$correo' and id_aplicacion = 4 and estado_sesion=1");
        if (empty($no_duplicate)) {
            $aplicacion_usuario = new Aplicacion_Usuario();
            $aplicacion_usuario->id_aplicacion = 4;
            $aplicacion_usuario->username = $correo;
            $aplicacion_usuario->name = $nombre;
            $aplicacion_usuario->fecha_ini = $dia->format('d-m-y');
            $aplicacion_usuario->estado_sesion = 1;
            $aplicacion_usuario->estado_validacion = 0;
            $aplicacion_usuario->save();

            $usuario_rol = new Usuario_Rol();
            $usuario_rol->id_aplicacion_usuario = $aplicacion_usuario->id_aplicacion_usuario;
            $usuario_rol->id_rol = $rol;
            $usuario_rol->id_empresa = $empresa;
            $usuario_rol->fecha_ini = $aplicacion_usuario->fecha_ini;
            if($radioEleccion == 1){
               

                $objeto_permitido = $request->selectObra;
                $obj_permitido = '';
                foreach ($objeto_permitido as $rl) {
                    $obj_permitido .= $rl.';';
                }
                $usuario_rol->objeto_permitido = substr($obj_permitido, 0, -1);
            }
            $usuario_rol->save();

            //print(json_encode($usuario_rol));

            Mail::to($correo)->send(new CorreoConfirmacion($nombre));
            Alert::success('El usuario se guardo correctamente','Guardado');
            return redirect()->route("gestion_user");
        }else {
            Alert::error('Usuario ya registrado en la plataforma.','Error');
            return redirect()->route("gestion_user");
        }
    }

    public function editUsuario(Request $request){
        try {
            //if(auth()->user()->rol[0]->id_rol != 0){
            if($request->selectPerfilEdit != -1){
                $id = $request->id_usuario;
                $selectPerfilEdit = $request->selectPerfil;
                $selectObra = $request->selectObra;
                $radioEleccion = $request->optradio;
                $user = Usuario_Rol::where('id_aplicacion_usuario',$id)->first();
                $user->id_rol = $selectPerfilEdit;
                if($radioEleccion == 1){
                    $objeto_permitido = $request->selectObra;
                    $obj_permitido = '';
                    foreach ($objeto_permitido as $rl) {
                        $obj_permitido .= $rl.';';
                    }
                    $user->objeto_permitido = substr($obj_permitido, 0, -1);
                }else{
                    $user->objeto_permitido = null;
                }
                $user->save();
                Alert::success('El usuario se actualizo correctamente','Actualizo');
                return redirect()->route("gestion_user");
            }else{
                Alert::error('No selecciono un Perfil','Error');
                return redirect()->route("gestion_user");
            }
            //}elseif(auth()->user()->rol[0]->id_rol == 7){
                /*Alert::warning('Usted no tiene privlegios para realizar cambios','Alerta');
                return redirect('/modulo_usuarios');*/
            //}
        } catch (Exception $e) {
            Alert::error('Ocurrio un error, vuelva a intentarlo.','Error');
            return redirect()->route("gestion_user");
        }
    }

}