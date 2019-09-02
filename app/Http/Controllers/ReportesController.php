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

class ReportesController extends Controller
{
	public function reportWeek(){
		$hoy = date('Y-m-d');
    	$año = date('Y');
    	$date = new DateTime($hoy);
		$week = $date->format("W");
		$week = $week - 1;
		$historicoCheckList = DB::select("select clbod_ano,clbod_semana,clbod_obra_id from abastecimiento.clbod GROUP BY clbod_ano,clbod_semana,clbod_obra_id ORDER BY clbod_ano DESC,clbod_semana DESC");
        return view('reports',compact('año','historicoCheckList','week'));
    }
}