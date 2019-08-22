<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\CreacionBodega;
use App\Models\UsuarioModel;
use App\Models\Aplicacion_Usuario;
use App\Models\Checklist_Bodega;
use App\Models\Checklist_Item;

class AbastecimientoController extends Controller
{
    public function index(){
            $email = auth()->user()->username;
            $perfil = auth()->user()->rol[0]->id_rol;
        	$hoy = date('Y-m-d');
        	$año = date('Y');
        	$date = new DateTime($hoy);
    		$week = $date->format("W");
    		$week = $week - 1;
         
        	$historicoCheckList = DB::select('select clbod_ano,clbod_semana from abastecimiento.clbod GROUP BY clbod_ano,clbod_semana ORDER BY clbod_ano DESC,clbod_semana DESC');
        	
            $accesos_permitidos = DB::select('select * from abastecimiento.accesos_permitidos where username =\''.$email.'\'');       
            $roles = DB::select('select * from seguridadapp.rol_aplicacion where id_aplicacion=4');
            $obj_permitido = '';
            $array_proyec = [];
            $objetos = array();

            if(isset($perfil)){
                $perf = DB::select('select * from seguridadapp.rol_aplicacion where id_rol = '.$perfil);
                if($perf[0]->id_rol == $perfil){
                    $rol = $perf[0]->nombre;
                }

                if(!empty($perf[0]->objeto_permitido)){
                    foreach($accesos_permitidos as $accesos){
                        $obj_permitido = explode(';',$accesos->objeto_permitido);
                        foreach($obj_permitido as $obj){
                            array_push($objetos, $obj);
                        }
                    }
                    
                    if(!empty($objetos)){
                        $obras_listas = '\'';
                        foreach($objetos as $key => $ob){
                            if(count($objetos) == ($key + 1)){
                                $obras_listas .= $ob;
                            }else{
                                $obras_listas .= $ob."','";
                            }

                            $client = new CLient([
                                'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
                            ]);
                            $response = $client->request('GET','proyectos?estado=A&id_proyecto='.$ob);
                            $proyectos = json_decode($response->getBody( )->getContents());
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
                                array_push($array_proyec, $pry);    
                            }
                        }
                        $tabla_bodega = DB::select("select p.clbod_id,p.clbod_obra_id,p.clbod_tipo,p.clbod_semana,p.clbod_ano,p.clbod_create_date,a.username clbod_create_user,p.clbod_validate_date,b.username clbod_validate_user,p.clbod_cumplimiento FROM abastecimiento.clbod p left join seguridadapp.aplicacion_usuario a on a.id_aplicacion_usuario = CAST( p.clbod_create_user AS integer) left join seguridadapp.aplicacion_usuario b on b.id_aplicacion_usuario = CAST( p.clbod_validate_user AS integer ) WHERE clbod_ano=$año AND clbod_semana=$week AND clbod_obra_id IN ($obras_listas') AND clbod_tipo= 1 ORDER BY clbod_ano DESC,clbod_semana DESC,clbod_create_date DESC");
                    }else{
                        $client = new CLient([
                            'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
                        ]);
                        $response = $client->request('GET','proyectos?estado=A');
                        $proyectos = json_decode($response->getBody( )->getContents());
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
                            array_push($array_proyec, $pry);    
                        }
                        $tabla_bodega = DB::select("select p.clbod_id,p.clbod_obra_id,p.clbod_tipo,p.clbod_semana,p.clbod_ano,p.clbod_create_date,a.username clbod_create_user,p.clbod_validate_date,b.username clbod_validate_user,p.clbod_cumplimiento FROM abastecimiento.clbod p left join seguridadapp.aplicacion_usuario a on a.id_aplicacion_usuario = CAST( p.clbod_create_user AS integer) left join seguridadapp.aplicacion_usuario b on b.id_aplicacion_usuario = CAST( p.clbod_validate_user AS integer ) WHERE clbod_ano=$año AND clbod_semana=$week AND clbod_tipo= 1 ORDER BY clbod_ano DESC,clbod_semana DESC,clbod_create_date DESC");
                    }

                }else{
                    $client = new CLient([
                        'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
                    ]);
                    $response = $client->request('GET','proyectos?estado=A');
                    $proyectos = json_decode($response->getBody( )->getContents());
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
                        array_push($array_proyec, $pry);    
                    }
                    $tabla_bodega = DB::select("select p.clbod_id,p.clbod_obra_id,p.clbod_tipo,p.clbod_semana,p.clbod_ano,p.clbod_create_date,a.username clbod_create_user,p.clbod_validate_date,b.username clbod_validate_user,p.clbod_cumplimiento FROM abastecimiento.clbod p left join seguridadapp.aplicacion_usuario a on a.id_aplicacion_usuario = CAST( p.clbod_create_user AS integer) left join seguridadapp.aplicacion_usuario b on b.id_aplicacion_usuario = CAST( p.clbod_validate_user AS integer ) WHERE clbod_ano=$año AND clbod_semana=$week AND clbod_tipo= 1 ORDER BY clbod_ano DESC,clbod_semana DESC,clbod_create_date DESC");
                }
            }else{
                $rol = 'INVITADO';
                $tabla_bodega = DB::select("select p.clbod_id,p.clbod_obra_id,p.clbod_tipo,p.clbod_semana,p.clbod_ano,p.clbod_create_date,a.username clbod_create_user,p.clbod_validate_date,b.username clbod_validate_user,p.clbod_cumplimiento FROM abastecimiento.clbod p left join seguridadapp.aplicacion_usuario a on a.id_aplicacion_usuario = CAST( p.clbod_create_user AS integer) left join seguridadapp.aplicacion_usuario b on b.id_aplicacion_usuario = CAST( p.clbod_validate_user AS integer ) WHERE clbod_ano=$año AND clbod_semana=$week AND clbod_tipo= 1 ORDER BY clbod_ano DESC,clbod_semana DESC,clbod_create_date DESC");
            }

            foreach($tabla_bodega as $bod){
                if (array_search($bod->clbod_obra_id, array_column($array_proyec, 'id_proyecto')) !== false) {
                    $key_proyecto = array_search($bod->clbod_obra_id, array_column($array_proyec, 'id_proyecto'));
                    $bod->unidad_negocio = $array_proyec[$key_proyecto]->cod_empresa;
                    $bod->obra = $array_proyec[$key_proyecto]->cod_empresa.' - '.$array_proyec[$key_proyecto]->nombre_proyecto; 
                }
            }
            return view('principal',compact('week','año','historicoCheckList','rol','roles','perfil','tabla_bodega'));
    }

    //BUSCAR CHECKLIST PANTALLA PRINCIPAL
    public function searchAbastecimiento(Request $request){
        $email = auth()->user()->username;
        $semana = $request->combobox_semana;
        $tipo = $request->combobox_tipo;
        $obj_permitido = explode('_',$semana);
        $año = $obj_permitido[0];
        $week = $obj_permitido[1];
        $proyectos_permitidos = '';
        $objetos = array();
        $array_proyec = [];

        $proyectos = DB::select("select objeto_permitido from seguridadapp.usuario_rol u inner join seguridadapp.aplicacion_usuario a on a.id_aplicacion_usuario = u.id_aplicacion_usuario where a.id_aplicacion=4 and a.username='$email'");
            
        if(!empty($proyectos[0]->objeto_permitido)){
            foreach($proyectos as $proyectos_permitidos){
                $proyectos_permitidos = explode(';',$proyectos_permitidos->objeto_permitido);
                    foreach($proyectos_permitidos as $obj){
                        array_push($objetos, $obj);
                    }
            }

            if(count($objetos) > 0){
                $obras_listas = '\'';
                    foreach($objetos as $key => $ob){
                        if(count($objetos) == ($key + 1)){
                            $obras_listas .= $ob;
                        }else{
                            $obras_listas .= $ob."','";
                        }

                        $client = new CLient([
                            'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
                        ]);
                        $response = $client->request('GET','proyectos?estado=A&id_proyecto='.$ob);
                        $proyectos = json_decode($response->getBody( )->getContents());
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
                            array_push($array_proyec, $pry);    
                        }
                    }
                $where = " and clbod_obra_id in ($obras_listas')";
            }else{
                $where = " and clbod_obra_id = $objetos";
            }
        }else{
            $client = new CLient([
                'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
            ]);
            $response = $client->request('GET','proyectos?estado=A');
            $proyectos = json_decode($response->getBody( )->getContents());
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
                array_push($array_proyec, $pry);    
            }
            $where = " ";
        }

        $busquedaList = DB::select("select p.clbod_id,p.clbod_obra_id,p.clbod_tipo,p.clbod_semana,p.clbod_ano,p.clbod_create_date,a.username clbod_create_user,p.clbod_validate_date,b.username clbod_validate_user,p.clbod_cumplimiento from abastecimiento.clbod p left join seguridadapp.aplicacion_usuario a on a.id_aplicacion_usuario = CAST( p.clbod_create_user AS integer) left join seguridadapp.aplicacion_usuario b on b.id_aplicacion_usuario = CAST( p.clbod_validate_user AS integer ) where clbod_semana = $week and clbod_ano=$año and clbod_tipo=$tipo $where ");

        if(!empty($busquedaList)){
            foreach($busquedaList as $bod){
                if (array_search($bod->clbod_obra_id, array_column($array_proyec, 'id_proyecto')) !== false) {
                    $key_proyecto = array_search($bod->clbod_obra_id, array_column($array_proyec, 'id_proyecto'));
                    $bod->unidad_negocio = $array_proyec[$key_proyecto]->cod_empresa;
                    $bod->obra = $array_proyec[$key_proyecto]->cod_empresa.' - '.$array_proyec[$key_proyecto]->nombre_proyecto; 
                }
            }
        }
        return $busquedaList;
    }

    //CARGA DE PANTALLA DEL MODULO DE BODEGA
    public function bodega(){
        $email = auth()->user()->username;
        $hoy = date('Y-m-d');
        $año = date('Y');
        $now = date('d/m/Y');
        $date = new DateTime($hoy);
        $week = $date->format("W");
        $week = $week - 1;
        $arreglo = array();
        $objetos = array();

        if(auth()->user()->rol[0]->id_rol == 10){
            $client = new CLient([
                'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
            ]);
            $response = $client->request('GET','proyectos?estado=A');
            $proyectos = json_decode($response->getBody( )->getContents());
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
        }else{
            $accesos_permitidos = DB::select('select * from abastecimiento.accesos_permitidos where username =\''.$email.'\'');
            foreach($accesos_permitidos as $accesos){
                $obj_permitido = explode(';',$accesos->objeto_permitido);
                foreach($obj_permitido as $obj){
                    array_push($objetos, $obj);
                }
            }

            if(count($objetos) > 0){
                $obras_listas = '\'';
                $proyectos = [];

                foreach($objetos as $key => $ob){
                    if(count($objetos) == ($key + 1)){
                        $obras_listas .= $ob;
                    }else{
                        $obras_listas .= $ob."','";
                    }

                    $client = new CLient([
                        'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
                    ]);
                    $response = $client->request('GET','proyectos?estado=A&id_proyecto='.$ob);
                    $proy = json_decode($response->getBody( )->getContents());
                    foreach($proy as $pry){
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
                        array_push($proyectos, $pry);
                    }
                }
            }else{
                $client = new CLient([
                'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
                ]);
                $response = $client->request('GET','proyectos?estado=A');
                $proyectos = json_decode($response->getBody( )->getContents());
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
            }
        }

        $checklist_padre = DB::select("select * from abastecimiento.clbod_preguntas where clbod_preguntas_estado = 1 and clbod_preguntas_tipo = 1 and clbod_preguntas_item_padre = 0 order by clbod_preguntas_item_id asc");
        
        foreach ($checklist_padre as $chk_p) {
             $detalle = (object) array(
                'id_cabecera' => $chk_p->clbod_preguntas_item_id,
                'cabecera' => $chk_p->clbod_preguntas_nombre,
                'tipo'=> $chk_p->clbod_preguntas_tipo,
                'estado'=> $chk_p->clbod_preguntas_estado,
                'hijo'=>  DB::select("select * from abastecimiento.clbod_preguntas where clbod_preguntas_estado = 1 and clbod_preguntas_tipo = 1 and clbod_preguntas_item_padre = $chk_p->clbod_preguntas_item_id order by clbod_preguntas_item_id asc")
            );
            array_push($arreglo,$detalle);
        }

        return view('bodega',compact('week','now','proyectos','email','arreglo'));
    }

    //VERIFICAR SI EXISTE CHECKLIST EN CLBOD
    public function verificateWeekB(Request $request){
        $obra = $request->comboObra;

        $verificate = Checklist_Bodega::where('clbod_obra_id',$obra)->where('clbod_tipo',1)->first();
        return $verificate;
    }

    //VERIFICAR SI EXISTE CHECKLIST EN CLBOD_ITEM
    public function verificateWeekBItem(Request $request){
        $obra = $request->comboObra;
        $hoy = date('Y-m-d');
        $date = new DateTime($hoy);    
        $año = date('Y');
        $week = $date->format("W");
        $week = $week - 1;

        $verificate = Checklist_Item::where('clbod_item_obra_id',$obra)
        ->where('clbod_item_tipo',1)
        ->where('clbod_item_ano',$año)
        ->where('clbod_item_semana',$week)
        ->first();
        return $verificate;
    }

    //GUARDAR CHECKLIST BODEGA BD:CLBOD
    public function saveCheckList(Request $request){
        $email = auth()->user()->username;
        $hoy = date('Y-m-d');
        $año = date('Y');
        $date = new DateTime($hoy);
        $week = $date->format("W");
        $week = $week - 1;
        $obra = $request->comboObra;
        $tipo = 1;
        $dia = new DateTime();
        $fecha_hoy = $dia->format('d-m-Y');

        $user = Aplicacion_Usuario::where('username',$email)->where('id_aplicacion',4)->first();

        $chk = new Checklist_Bodega();
        $chk->clbod_obra_id = $obra;
        $chk->clbod_tipo = $tipo;
        $chk->clbod_semana = $week;
        $chk->clbod_ano = $año;
        $chk->clbod_create_date = $fecha_hoy;
        $chk->clbod_create_user = $user->id_aplicacion_usuario;
        $chk->save();

        $client = new CLient([
            'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
        ]);
        $response = $client->request('GET','proyectos?estado=A&id_proyecto='.$obra);
        $proyectos = json_decode($response->getBody( )->getContents());
        foreach($proyectos as $pry){
            if($pry->cod_empresa == '0004'){
                $pry->cod_empresa = 'DVC - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0006'){
                $pry->cod_empresa = 'FE - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0010'){
                $pry->cod_empresa = 'FA - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0018'){
                $pry->cod_empresa = 'FP - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0019'){
                $pry->cod_empresa = 'FAI - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0020'){
                $pry->cod_empresa = 'FT - '.$pry->nombre_proyecto;
            }

        }

        $cod_empresa = $proyectos[0]->cod_empresa;
        $cheklist = [
            "cod_empresa" => $cod_empresa,
            "tipo" => 'Checklist Bodega',
            "semana" => $week,
            "año" => $año,
            "create_date" => $fecha_hoy,
            "create_user" => $email,
        ];              

        $usuarios = DB::select('select name,username from seguridadapp.aplicacion_usuario a inner join seguridadapp.usuario_rol u on u.id_aplicacion_usuario = a.id_aplicacion_usuario where a.id_aplicacion = 4 and a.estado_sesion = 1 and a.estado_validacion = 1 and u.id_rol = 10'); 

        if(!empty($usuarios)){
            foreach($usuarios as $usr){
                $name = $usr->name;
                $tipox = $cheklist['tipo'];
                $proyectox = $cheklist['cod_empresa'];
                Mail::to($usr->username)->send(new CreacionBodega($name,$tipox,$proyectox,$week));
            }
        }
        return $cheklist;
    }

    //GUARDAR CHECKLIST BODEGA BD:CLBOD_ITEM
    public function saveCheckListBodegaItem(Request $request){
        $id_usuario = auth()->user()->id_aplicacion_usuario;
        $obra = $request->comboObra;
        $hoy = date('Y-m-d');
        $año = date('Y');
        $date = new DateTime($hoy);
        $week = $date->format("W");
        $week = $week - 1;
        $dia = new DateTime();
        $fecha_hoy = $dia->format('d-m-Y');
        $array_item = $request->rev;

        
        $buenas = 0;
        $preguntas_input = 0;
        $preguntas_select = 0;

        foreach($array_item as $rev){
            if($rev == 'S' || $rev == 'N'){
                $preguntas_select++;
            }else{
                $preguntas_input++;
            }
        }
        $preguntas_totales = $preguntas_input + $preguntas_select;

        $count = 0;
        $porcentaje_total = 0;
        foreach ($array_item as $key) {
            if($key == 'S' || $key == 'N'){
                $porcen = 0.75;
                if($key == 'S'){
                    $porcent_ind = round((($porcen / $preguntas_select)*100),2);
                }else if($key == 'N'){
                    $porcent_ind = 0;
                }
            }else{
                $porcen = 0.25;
                if($key == null){
                    $key = 0;
                }

                if($key < 5){
                    $porcent_ind = round((($porcen / $preguntas_input)*100),2);
                }else{
                    $porcent_ind = 0;
                }
            }
            
            $item = new Checklist_Item();
            $item->clbod_item_obra_id = $obra;
            $item->clbod_item_tipo = 1;
            $item->clbod_item_semana = $week;
            $item->clbod_item_ano = $año;
            $item->clbod_item_cumple = $key;
            $item->clbod_item_preguntas_id = $request->id[$count];
            $item->clbod_item_por = $porcent_ind;
            $item->clbod_item_create_date = $fecha_hoy;
            $item->clbod_item_create_user = $id_usuario;
            $item->save();

            $porcentaje_total = $porcentaje_total + $porcent_ind;
            if($porcentaje_total > 100){
                $porcentaje_total = 100;
            }
            $count++;
        }

        $update_check_bodega = DB::select("update abastecimiento.clbod SET clbod_cumplimiento = $porcentaje_total WHERE clbod_obra_id = '$obra' and clbod_tipo =1 and clbod_semana = $week and clbod_ano = $año;");

        return $item;    
    }

    //VALIDACION DEL CHECKLIST BODEGA
    public function validateWeekBodega(Request $request){
        $email = auth()->user()->id_aplicacion_usuario;
        $obra = $request->obra_id;
        $week = $request->week;
        $año = $request->year;
        $dia = new DateTime();
        $fecha_hoy = $dia->format('d-m-Y');

        $check_bodega = Checklist_Bodega::where('clbod_obra_id',$obra)
                                                ->where('clbod_tipo',1)
                                                ->where('clbod_semana',$week)
                                                ->where('clbod_ano',$año)
                                                ->first();

        $check_bodega->clbod_validate_user = $email;
        $check_bodega->clbod_validate_date = $fecha_hoy;
        $check_bodega->save();

        return $check_bodega;
    }

    //CARGAR EL MODULO DE VALIDACION U OBSERVACION DEL CHECKLIST BODEGA CREADO
    public function editOldBodega($id){
        $id_obra = $id;
        $semana = $_GET['week'];
        $year = $_GET['año'];
        $arreglo = array();
        
        $client = new CLient([
            'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
        ]);
        $response = $client->request('GET','proyectos?estado=A&id_proyecto='.$id_obra);
        $proyectos = json_decode($response->getBody( )->getContents());
        foreach($proyectos as $pry){
            if($pry->cod_empresa == '0004'){
                $pry->cod_empresa = 'DVC - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0006'){
                $pry->cod_empresa = 'FE - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0010'){
                $pry->cod_empresa = 'FA - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0018'){
                $pry->cod_empresa = 'FP - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0019'){
                $pry->cod_empresa = 'FAI - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0020'){
                $pry->cod_empresa = 'FT - '.$pry->nombre_proyecto;
            }
        }

         $checklist = DB::select("select clbod_create_user,clbod_create_date,clbod_cumplimiento,clbod_validate_user from abastecimiento.clbod i where clbod_obra_id='$id_obra' and clbod_semana=$semana and clbod_ano=$year and clbod_tipo = 1");

        $checklist_padre = DB::select("select p.clbod_preguntas_item_id, p.clbod_preguntas_item_padre,clbod_preguntas_nombre,p.clbod_preguntas_estado, p.clbod_preguntas_tipo,r.clbod_item_cumple from abastecimiento.clbod_preguntas p left join( select clbod_preguntas_item_id,clbod_preguntas_item_padre,clbod_item_preguntas_id,clbod_item_cumple from abastecimiento.clbod_item i inner join abastecimiento.clbod_preguntas p on p.clbod_preguntas_item_id = i.clbod_item_preguntas_id and p.clbod_preguntas_item_padre = 0)r on r.clbod_preguntas_item_id = p.clbod_preguntas_item_id where p.clbod_preguntas_tipo = 1 and p.clbod_preguntas_item_padre = 0 and p.clbod_preguntas_estado = 1 order by p.clbod_preguntas_item_id asc");

         foreach ($checklist_padre as $chk_p) {
             $detalle = (object) array(
                'id_cabecera' => $chk_p->clbod_preguntas_item_id,
                'cabecera' => $chk_p->clbod_preguntas_nombre,
                'tipo'=> $chk_p->clbod_preguntas_tipo,
                'estado'=> $chk_p->clbod_preguntas_estado,
                'value' => $chk_p->clbod_item_cumple,
                'hijo'=>  DB::select("select clbod_item_preguntas_id,clbod_preguntas_nombre,p.clbod_preguntas_item_padre,clbod_item_cumple from abastecimiento.clbod_item i inner join abastecimiento.clbod_preguntas p on p.clbod_preguntas_item_id = i.clbod_item_preguntas_id where clbod_item_obra_id='$id_obra' and clbod_item_semana=$semana and clbod_item_ano=$year and clbod_item_tipo = 1 and clbod_preguntas_item_padre = $chk_p->clbod_preguntas_item_id order by clbod_preguntas_item_id asc")
            );
            array_push($arreglo,$detalle);
        }
        return view('editbodega',compact('semana','arreglo','checklist','proyectos'));
    }



    //CARGA DE PANTALLA DEL MODULO DE BODEGA
    public function visita(){
        $email = auth()->user()->username;
        $hoy = date('Y-m-d');
        $año = date('Y');
        $now = date('d/m/Y');
        $date = new DateTime($hoy);
        $week = $date->format("W");
        $week = $week - 1;
        $arreglo = array();
        $objetos = array();

        if(auth()->user()->rol[0]->id_rol == 10){
            $client = new CLient([
                'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
            ]);
            $response = $client->request('GET','proyectos?estado=A');
            $proyectos = json_decode($response->getBody( )->getContents());
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
        }else{
            $accesos_permitidos = DB::select('select * from abastecimiento.accesos_permitidos where username =\''.$email.'\'');
            foreach($accesos_permitidos as $accesos){
                $obj_permitido = explode(';',$accesos->objeto_permitido);
                foreach($obj_permitido as $obj){
                    array_push($objetos, $obj);
                }
            }

            if(count($objetos) > 0){
                $obras_listas = '\'';
                $proyectos = [];

                foreach($objetos as $key => $ob){
                    if(count($objetos) == ($key + 1)){
                        $obras_listas .= $ob;
                    }else{
                        $obras_listas .= $ob."','";
                    }

                    $client = new CLient([
                        'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
                    ]);
                    $response = $client->request('GET','proyectos?estado=A&id_proyecto='.$ob);
                    $proy = json_decode($response->getBody( )->getContents());
                    foreach($proy as $pry){
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
                        array_push($proyectos, $pry);
                    }
                }
            }else{
                $client = new CLient([
                'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
                ]);
                $response = $client->request('GET','proyectos?estado=A');
                $proyectos = json_decode($response->getBody( )->getContents());
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
            }
        }

        $checklist_padre = DB::select("select * from abastecimiento.clbod_preguntas where clbod_preguntas_estado = 1 and clbod_preguntas_tipo = 2 and clbod_preguntas_item_padre = 0 order by clbod_preguntas_item_id asc");
        
        foreach ($checklist_padre as $chk_p) {
             $detalle = (object) array(
                'id_cabecera' => $chk_p->clbod_preguntas_item_id,
                'cabecera' => $chk_p->clbod_preguntas_nombre,
                'tipo'=> $chk_p->clbod_preguntas_tipo,
                'estado'=> $chk_p->clbod_preguntas_estado,
                'hijo'=>  DB::select("select * from abastecimiento.clbod_preguntas where clbod_preguntas_estado = 1 and clbod_preguntas_tipo = 2 and clbod_preguntas_item_padre = $chk_p->clbod_preguntas_item_id order by clbod_preguntas_item_id asc")
            );
            array_push($arreglo,$detalle);
        }
        return view('visita',compact('week','now','proyectos','email','arreglo'));
    }

    //VERIFICAR SI EXISTE CHECKLIST VISITA EN CLBOD
    public function verificateWeekV(Request $request){
        $obra = $request->comboObra;

        $verificate = Checklist_Bodega::where('clbod_obra_id',$obra)->where('clbod_tipo',2)->first();
        return $verificate;
    }

    //VERIFICAR SI EXISTE CHECKLIST VISITA EN CLBOD_ITEM
    public function verificateWeekVItem(Request $request){
        $obra = $request->comboObra;
        $hoy = date('Y-m-d');
        $date = new DateTime($hoy);    
        $año = date('Y');
        $week = $date->format("W");
        $week = $week - 1;

        $verificate = Checklist_Item::where('clbod_item_obra_id',$obra)
        ->where('clbod_item_tipo',2)
        ->where('clbod_item_ano',$año)
        ->where('clbod_item_semana',$week)
        ->first();
        return $verificate;
    }

    //GUARDA CHECLKLIST VISITA EN CLBOD
    public function saveCheckListVisita(Request $request){
        $email = auth()->user()->username;
        $hoy = date('Y-m-d');
        $año = date('Y');
        $date = new DateTime($hoy);
        $week = $date->format("W");
        $week = $week - 1;
        $obra = $request->comboObra;
        $tipo = 2;
        $dia = new DateTime();
        $fecha_hoy = $dia->format('d-m-Y');

        $user = Aplicacion_Usuario::where('username',$email)->where('id_aplicacion',4)->first();

        $chk = new Checklist_Bodega();
        $chk->clbod_obra_id = $obra;
        $chk->clbod_tipo = $tipo;
        $chk->clbod_semana = $week;
        $chk->clbod_ano = $año;
        $chk->clbod_create_date = $fecha_hoy;
        $chk->clbod_create_user = $user->id_aplicacion_usuario;
        $chk->save();

        $client = new CLient([
            'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
        ]);
        $response = $client->request('GET','proyectos?estado=A&id_proyecto='.$obra);
        $proyectos = json_decode($response->getBody( )->getContents());
        foreach($proyectos as $pry){
            if($pry->cod_empresa == '0004'){
                $pry->cod_empresa = 'DVC - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0006'){
                $pry->cod_empresa = 'FE - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0010'){
                $pry->cod_empresa = 'FA - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0018'){
                $pry->cod_empresa = 'FP - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0019'){
                $pry->cod_empresa = 'FAI - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0020'){
                $pry->cod_empresa = 'FT - '.$pry->nombre_proyecto;
            }

        }

        $cod_empresa = $proyectos[0]->cod_empresa;
        $cheklist = [
            "cod_empresa" => $cod_empresa,
            "tipo" => 'Checklist Visita',
            "semana" => $week,
            "año" => $año,
            "create_date" => $fecha_hoy,
            "create_user" => $email,
        ];      

        $usuarios = DB::select('select name,username from seguridadapp.aplicacion_usuario a inner join seguridadapp.usuario_rol u on u.id_aplicacion_usuario = a.id_aplicacion_usuario where a.id_aplicacion = 4 and a.estado_sesion = 1 and a.estado_validacion = 1 and u.id_rol = 10'); 

        if(!empty($usuarios)){
            foreach($usuarios as $usr){
                $name = $usr->name;
                $tipox = $cheklist['tipo'];
                $proyectox = $cheklist['cod_empresa'];
                Mail::to($usr->username)->send(new CreacionBodega($name,$tipox,$proyectox,$week));
            }
        }        
        return $cheklist;
    }

    //GUARDAR CHECKLIST BODEGA BD:CLBOD_ITEM
    public function saveCheckListVisitaItem(Request $request){
        $id_usuario = auth()->user()->id_aplicacion_usuario;
        $obra = $request->comboObra;
        $hoy = date('Y-m-d');
        $año = date('Y');
        $date = new DateTime($hoy);
        $week = $date->format("W");
        $week = $week - 1;
        $dia = new DateTime();
        $fecha_hoy = $dia->format('d-m-Y');
        $array_item = $request->rev;

        
        $buenas = 0;
        $preguntas_input = 0;
        $preguntas_select = 0;

        foreach($array_item as $rev){
            if($rev == 'S' || $rev == 'N'){
                $preguntas_select++;
            }else{
                $preguntas_input++;
            }
        }
        $preguntas_totales = $preguntas_input + $preguntas_select;

        $count = 0;
        $porcentaje_total = 0;
        foreach ($array_item as $key) {
            if($key == 'S' || $key == 'N'){
                $porcen = 95;
                if($key == 'S'){
                    $porcent_ind = ($porcen / $preguntas_select);
                }else if($key == 'N'){
                    $porcent_ind = 0;
                }
            }else{
                $porcen = 5;
                if($key == null){
                    $key = 1;
                }else{
                    $porcent_ind = $key;
                }
            }

            $item = new Checklist_Item();
            $item->clbod_item_obra_id = $obra;
            $item->clbod_item_tipo = 2;
            $item->clbod_item_semana = $week;
            $item->clbod_item_ano = $año;
            $item->clbod_item_cumple = $key;
            $item->clbod_item_preguntas_id = $request->id[$count];
            $item->clbod_item_por = round($porcent_ind,2);
            $item->clbod_item_create_date = $fecha_hoy;
            $item->clbod_item_create_user = $id_usuario;
            $item->save();

            $porcentaje_total = $porcentaje_total + $porcent_ind;
            $porcentaje_total = round($porcentaje_total,2);
            if($porcentaje_total > 100){
                $porcentaje_total = 100;
            }
            $count++;
        }

        $update_check_bodega = DB::select("update abastecimiento.clbod SET clbod_cumplimiento = $porcentaje_total WHERE clbod_obra_id = '$obra' and clbod_tipo =2 and  clbod_semana = $week and clbod_ano = $año;");

        return $item;    
    }

    //VALIDACION DEL CHECKLIST BODEGA
    public function validateWeekVisita(Request $request){
        $id_usuario = auth()->user()->id_aplicacion_usuario;
        $obra = $request->obra_id;
        $week = $request->week;
        $año = $request->year;
        $dia = new DateTime();
        $fecha_hoy = $dia->format('d-m-Y');

        $check_bodega = Checklist_Bodega::where('clbod_obra_id',$obra)
                                                ->where('clbod_tipo',2)
                                                ->where('clbod_semana',$week)
                                                ->where('clbod_ano',$año)
                                                ->first();

        $check_bodega->clbod_validate_user = $id_usuario;
        $check_bodega->clbod_validate_date = $fecha_hoy;
        $check_bodega->save();

        return $check_bodega;
    }

    //CARGAR EL MODULO DE VALIDACION U OBSERVACION DEL CHECKLIST VISITA CREADO
    public function editOldVisita($id){
        $id_obra = $id;
        $semana = $_GET['week'];
        $year = $_GET['año'];
        $arreglo = array();
        
        $client = new CLient([
            'base_uri' => 'http://10.0.0.14:1337/datos_maestros/',
        ]);
        $response = $client->request('GET','proyectos?estado=A&id_proyecto='.$id_obra);
        $proyectos = json_decode($response->getBody( )->getContents());
        foreach($proyectos as $pry){
            if($pry->cod_empresa == '0004'){
                $pry->cod_empresa = 'DVC - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0006'){
                $pry->cod_empresa = 'FE - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0010'){
                $pry->cod_empresa = 'FA - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0018'){
                $pry->cod_empresa = 'FP - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0019'){
                $pry->cod_empresa = 'FAI - '.$pry->nombre_proyecto;
            }else if($pry->cod_empresa == '0020'){
                $pry->cod_empresa = 'FT - '.$pry->nombre_proyecto;
            }
        }

         $checklist = DB::select("select clbod_create_user,clbod_create_date,clbod_cumplimiento,clbod_validate_user from abastecimiento.clbod i where clbod_obra_id='$id_obra' and clbod_semana=$semana and clbod_ano=$year and clbod_tipo = 2");

        $checklist_padre = DB::select("select p.clbod_preguntas_item_id, p.clbod_preguntas_item_padre,clbod_preguntas_nombre,p.clbod_preguntas_estado, p.clbod_preguntas_tipo,r.clbod_item_cumple from abastecimiento.clbod_preguntas p left join( select clbod_preguntas_item_id,clbod_preguntas_item_padre,clbod_item_preguntas_id,clbod_item_cumple from abastecimiento.clbod_item i inner join abastecimiento.clbod_preguntas p on p.clbod_preguntas_item_id = i.clbod_item_preguntas_id and p.clbod_preguntas_item_padre = 0)r on r.clbod_preguntas_item_id = p.clbod_preguntas_item_id where p.clbod_preguntas_tipo = 2 and p.clbod_preguntas_item_padre = 0 and p.clbod_preguntas_estado = 1 order by p.clbod_preguntas_item_id asc");

         foreach ($checklist_padre as $chk_p) {
             $detalle = (object) array(
                'id_cabecera' => $chk_p->clbod_preguntas_item_id,
                'cabecera' => $chk_p->clbod_preguntas_nombre,
                'tipo'=> $chk_p->clbod_preguntas_tipo,
                'estado'=> $chk_p->clbod_preguntas_estado,
                'value' => $chk_p->clbod_item_cumple,
                'hijo'=>  DB::select("select clbod_item_preguntas_id,clbod_preguntas_nombre,p.clbod_preguntas_item_padre,clbod_item_cumple from abastecimiento.clbod_item i inner join abastecimiento.clbod_preguntas p on p.clbod_preguntas_item_id = i.clbod_item_preguntas_id where clbod_item_obra_id='$id_obra' and clbod_item_semana=$semana and clbod_item_ano=$year and clbod_item_tipo = 2 and clbod_preguntas_item_padre = $chk_p->clbod_preguntas_item_id order by clbod_preguntas_item_id asc")
            );
            array_push($arreglo,$detalle);
        }
        //print(json_encode($arreglo));
        return view('editvisita',compact('semana','arreglo','checklist','proyectos'));
    }
    
    
}
