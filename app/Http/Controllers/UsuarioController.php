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
    public function __construct()
    {
        $this->middleware('auth');
    }

    
	public function usuariosListing(){
    	try {
           if(auth()->user()->rol[0]->id_rol == 14 ){
                abort(401);
            }else{
                $table_usuario = UsuarioModel::rol_aplicacion();
                return view('usuario',compact('table_usuario'));
            } 
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function addNewUsuario(){
        try {
            $perfil = DB::select('select id_rol,upper(nombre) as nombre from seguridadapp.rol_aplicacion where id_aplicacion = 4');
            $client = new CLient([
                'base_uri' => 'http://10.0.0.14:1337/datos_maestros/'
            ]);
            $response = $client->request('GET','empresa');
            $empresa = json_decode($response->getBody( )->getContents());
            $response1 = $client->request('GET','unidades_negocio');
            $unidades_negocio = json_decode($response1->getBody( )->getContents());
            $proyectos = DB::connection('pgsqlProye')->select('select * from ggo.ggo_proyecto where es_vigente = 1 order by id_unidad_negocio asc');
            foreach($proyectos as $pry){
                if($pry->id_unidad_negocio == '0004'){
                    $pry->id_unidad_negocio = 'DVC';
                }else if($pry->id_unidad_negocio == '0006'){
                    $pry->id_unidad_negocio = 'FE';
                }else if($pry->id_unidad_negocio == '0010'){
                    $pry->id_unidad_negocio = 'FA';
                }else if($pry->id_unidad_negocio == '0018'){
                    $pry->id_unidad_negocio = 'FP';
                }else if($pry->id_unidad_negocio == '0018-OP'){
                    $pry->id_unidad_negocio = 'FOP';
                }else if($pry->id_unidad_negocio == '0018-OC'){
                    $pry->id_unidad_negocio = 'FOC';
                }else if($pry->id_unidad_negocio == '0019'){
                    $pry->id_unidad_negocio = 'FAI';
                }else if($pry->id_unidad_negocio == '0020'){
                    $pry->id_unidad_negocio = 'FT';
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
            if(!empty($array_cod)){
                foreach($array_cod as $cod){
                    $en_uso = array_search($cod, array_column($proyectos, 'cod_proyecto'));
                    unset($proyectos[$en_uso]);
                    $proyectos = array_values($proyectos);
                }
            }
            return view('usuarios/addNew',compact('perfil','empresa','unidades_negocio','proyectos','array_cod'));
        } catch (Exception $e) {
            abort(500);
        }
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
            $id_usuario = $usuario_app[0]->id_aplicacion_usuario;
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
                    $sr_proyectos = DB::connection('pgsqlProye')->select("select * from ggo.ggo_proyecto where cod_proyecto='$obj'");

                        foreach($sr_proyectos as $sr_proyectos){
                            if($sr_proyectos->id_unidad_negocio == '0004'){
                                $sr_proyectos->id_unidad_negocio = 'DVC';
                            }else if($sr_proyectos->id_unidad_negocio == '0006'){
                                $sr_proyectos->id_unidad_negocio = 'FE';
                            }else if($sr_proyectos->id_unidad_negocio == '0010'){
                                $sr_proyectos->id_unidad_negocio = 'FA';
                            }else if($sr_proyectos->id_unidad_negocio == '0018'){
                                $sr_proyectos->id_unidad_negocio = 'FP';
                            }else if($sr_proyectos->id_unidad_negocio == '0019'){
                                $sr_proyectos->id_unidad_negocio = 'FAI';
                            }else if($sr_proyectos->id_unidad_negocio == '0020'){
                                $sr_proyectos->id_unidad_negocio = 'FT';
                            }
                            $obj_permitido .= $sr_proyectos->id_unidad_negocio .' - '. $sr_proyectos->nombre_proyecto.';';

                        }
                        $contenido_objeto[] = ($sr_proyectos);
                }
            }else{
                $obj_permitido = '0';
            }
            $proyectos = DB::connection('pgsqlProye')->select('select * from ggo.ggo_proyecto where es_vigente = 1 order by id_unidad_negocio asc');
            foreach($proyectos as $pry){
                if($pry->id_unidad_negocio == '0004'){
                    $pry->id_unidad_negocio = 'DVC';
                }else if($pry->id_unidad_negocio == '0006'){
                    $pry->id_unidad_negocio = 'FE';
                }else if($pry->id_unidad_negocio == '0010'){
                    $pry->id_unidad_negocio = 'FA';
                }else if($pry->id_unidad_negocio == '0018'){
                    $pry->id_unidad_negocio = 'FP';
                }else if($pry->id_unidad_negocio == '0019'){
                    $pry->id_unidad_negocio = 'FAI';
                }else if($pry->id_unidad_negocio == '0020'){
                    $pry->id_unidad_negocio = 'FT';
                }
            }

            $list_proy_exi = DB::select("select distinct(u.objeto_permitido) from seguridadapp.usuario_rol u inner join seguridadapp.aplicacion_usuario a on a.id_aplicacion_usuario = u.id_aplicacion_usuario where a.id_aplicacion = 4 and u.objeto_permitido is not null and a.id_aplicacion_usuario <> $id_usuario");
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
            if(!empty($array_cod)){
                foreach($array_cod as $cod){
                    $en_uso = array_search($cod, array_column($proyectos, 'cod_proyecto'));
                    unset($proyectos[$en_uso]);
                    $proyectos = array_values($proyectos);
                }
            }

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