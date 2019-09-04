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
		$historicoCheckList = DB::select("select distinct clbod_semana,clbod_ano from abastecimiento.clbod where clbod_validate_date is not null GROUP BY clbod_ano,clbod_semana,clbod_obra_id ORDER BY clbod_ano DESC,clbod_semana DESC");

		$historicoProy = DB::select("select distinct(b.clbod_obra_id),p.nom_cod_proyecto from abastecimiento.clbod b inner join ggo.ggo_proyecto p on trim(p.cod_proyecto) = b.clbod_obra_id where b.clbod_validate_date is not null GROUP BY clbod_ano,clbod_semana,clbod_obra_id,p.nom_cod_proyecto ");

		$reporte_seguimiento = DB::select("select trim(p.cod_proyecto) cod_proyecto,p.nom_cod_proyecto,p.nombre_proyecto,a.clbod_cumplimiento as cumplimiento_almacen,v.clbod_cumplimiento as cumplimiento_visita, (b.clbod_ano || '_' || b.clbod_semana)as semana,b.clbod_semana,b.clbod_ano from abastecimiento.clbod b full join (select clbod_obra_id,clbod_cumplimiento,clbod_semana from abastecimiento.clbod where clbod.clbod_tipo = 1)a on a.clbod_obra_id = b.clbod_obra_id and a.clbod_semana = b.clbod_semana full join (select clbod_obra_id,clbod_cumplimiento,clbod_semana from abastecimiento.clbod where clbod.clbod_tipo = 2)v on v.clbod_obra_id = b.clbod_obra_id and v.clbod_semana = b.clbod_semana inner join ggo.ggo_proyecto p on trim(p.cod_proyecto) = b.clbod_obra_id where b.clbod_validate_date is not null group by p.cod_proyecto, p.nom_cod_proyecto, p.nombre_proyecto, a.clbod_cumplimiento, v.clbod_cumplimiento,b.clbod_semana,b.clbod_ano order by b.clbod_semana,b.clbod_ano"); 

		$historicoVisitaWeek = DB::select("select distinct clbod_semana,clbod_ano from abastecimiento.clbod  GROUP BY clbod_ano,clbod_semana,clbod_obra_id ORDER BY clbod_ano DESC,clbod_semana DESC");
		//print(json_encode($historicoProy));
        return view('reports',compact('año','historicoCheckList','week','reporte_seguimiento','historicoProy','historicoVisitaWeek'));
    }
}