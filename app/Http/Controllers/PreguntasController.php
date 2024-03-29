<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Preguntas;
use DateTime;
use Alert;

class PreguntasController extends Controller
{
	public function generateQuestions(){
        try {
            $arreglo = array();
            $tabla_padre = DB::select("select * from abastecimiento.clbod_preguntas where clbod_preguntas_item_padre = 0  and clbod_preguntas_estado = 1 order by clbod_preguntas_nombre asc");
            foreach ($tabla_padre as $rl) {
                $detalle = (object) array(
                    'id_cabecera' => $rl->clbod_preguntas_item_id,
                    'cabecera' => $rl->clbod_preguntas_nombre,
                    'estado'=> $rl->clbod_preguntas_estado,
                    'hijo'=>  DB::select("select * from abastecimiento.clbod_preguntas where clbod_preguntas_item_padre = $rl->clbod_preguntas_item_id and clbod_preguntas_estado = 1 order by clbod_preguntas_item_id asc")
                );
                array_push($arreglo,$detalle);
            }
            if(auth()->user()->rol[0]->id_rol == 14 ){
                abort(401);
            }else{
                return view('questions',compact('arreglo'));
            }
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function addNewPregunta(){
        try {
            return view('preguntas/addNewQ');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function addPregunta(Request $request){
        try {
            $dia = new DateTime();
            $dia->format('d-m-y');
            $preguntas = new Preguntas();
            
            if($request->inputId == 0){
                $preguntas->clbod_preguntas_item_padre = $request->inputId;
                $preguntas->clbod_preguntas_nombre = $request->inputNombre;
                $preguntas->clbod_preguntas_usuario_creacion = auth()->user()->id_aplicacion_usuario;
                $preguntas->clbod_preguntas_fecha_creacion = $dia->format('d-m-y');
                $preguntas->clbod_preguntas_estado = 1;
                $mnj_alerta = 'El Tópico fue registrado exictosamente';
            }else{
                $padre = Preguntas::where('clbod_preguntas_item_id',$request->inputId)->first();
                $preguntas->clbod_preguntas_item_padre = $request->inputId;
                $preguntas->clbod_preguntas_nombre = $request->inputNombre;
                $preguntas->clbod_preguntas_usuario_creacion = auth()->user()->id_aplicacion_usuario;
                $preguntas->clbod_preguntas_fecha_creacion = $dia->format('d-m-y');
                $preguntas->clbod_preguntas_estado = 1;
                $mnj_alerta = 'La Pregunta fue registrada exictosamente';
            }
            $preguntas->save();
            Alert::success($mnj_alerta,'GUARDADO');
            return redirect('/preguntas_checklist');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function editOldPregunta($id){
        try {
            $preguntas = Preguntas::where('clbod_preguntas_item_id',$id)->first();
            if (!empty($preguntas)) {
                return view('preguntas/editOldQ',compact('preguntas'));
            }else {
                echo '<div class="text-center"><img height="250px" src="img/error.png" /><h3>OCRURRIO UN ERROR</h3></div>';
            }
        } catch (Exception $e) {
            echo '<div class="text-center"><img height="250px" src="img/error.png" /><h3>Ocurrio un error</h3></div>';
        }
    }

    public function editPregunta(Request $request){
        try {
            $id = $request->id;
            $dia = new DateTime();
            $dia->format('d-m-y');
            $preguntas = Preguntas::where('clbod_preguntas_item_id',$id)->first();

            if($request->inputId == 0){
                $preguntas->clbod_preguntas_item_padre = $request->inputId;
                $preguntas->clbod_preguntas_nombre = $request->inputNombre;
                $preguntas->clbod_preguntas_usuario_modificacion = auth()->user()->id_aplicacion_usuario;
                $preguntas->clbod_preguntas_fecha_modificacion = $dia->format('d-m-y');
                $preguntas->clbod_preguntas_estado = 1;
                $mnj_alerta = 'El Tópico fue actualizado exictosamente';
            }else{
                $preguntas->clbod_preguntas_item_padre = $request->inputId;
                $preguntas->clbod_preguntas_nombre = $request->inputNombre;
                $preguntas->clbod_preguntas_usuario_modificacion = auth()->user()->id_aplicacion_usuario;
                $preguntas->clbod_preguntas_fecha_modificacion = $dia->format('d-m-y');
                $preguntas->clbod_preguntas_estado = 1;
                $mnj_alerta = 'La Pregunta fue actualizada exictosamente';
            }
            $preguntas->save();
            Alert::success($mnj_alerta,'ACTUALIZADO');
            return redirect('/preguntas_checklist');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function setEstado($id){
        try {
            $dia = new DateTime();
            $dia->format('d-m-y');
            $preguntas = Preguntas::where('clbod_preguntas_item_id',$id)->first();
            $preguntas->clbod_preguntas_usuario_modificacion = auth()->user()->id_aplicacion_usuario;
            $preguntas->clbod_preguntas_fecha_modificacion = $dia->format('d-m-y');
            if($preguntas->clbod_preguntas_estado == 1){
                $preguntas->clbod_preguntas_estado = 0;
                if($preguntas->clbod_preguntas_item_padre == 1){
                    $mensaje = 'La pregunta fue desactivada';
                    $title = 'Desactivada';  
                }else{
                    $mensaje = 'El Tópico fue desactivado';
                    $title = 'Desactivado';  
                }
            }else{
                $preguntas->clbod_preguntas_estado = 1;
                if($preguntas->clbod_preguntas_item_padre == 1){
                    $mensaje = 'La pregunta fue activada';
                    $title = 'Activada';  
                }else{
                    $mensaje = 'El Tópico fue activado';
                    $title = 'Activado';  
                }
            }
            $preguntas->save();
            Alert::success($mensaje,$title);
            return redirect('/preguntas_checklist');
        } catch (Exception $e) {
            abort(500);
        }
    }

}