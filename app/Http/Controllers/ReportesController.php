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

		$tabla_visita = DB::select("select b.clbod_semana,b.clbod_ano,b.clbod_obra_id,p.nombre_proyecto,COALESCE(a.estado,'no_existe') as almacen,COALESCE(v.estado,'no_existe') as visita,b.clbod_ano||'_'||b.clbod_semana fecha from abastecimiento.clbod b full join (select clbod_obra_id,clbod_cumplimiento,clbod_semana,case when clbod_validate_date is null then 'pendiente' when clbod_validate_date is not null then 'aprobado' else 'no_existe' end estado from abastecimiento.clbod where clbod.clbod_tipo = 1)a on a.clbod_obra_id = b.clbod_obra_id and a.clbod_semana = b.clbod_semana full join (select clbod_obra_id,clbod_cumplimiento,clbod_semana , case	when clbod_validate_date is null then 'pendiente' when clbod_validate_date is not null then 'aprobado' else 'no_existe' end estado  from abastecimiento.clbod where clbod.clbod_tipo = 2 )v on v.clbod_obra_id = b.clbod_obra_id and v.clbod_semana = b.clbod_semana inner join ggo.ggo_proyecto p on trim(p.cod_proyecto) = b.clbod_obra_id GROUP BY b.clbod_semana,b.clbod_ano,b.clbod_obra_id,a.estado,v.estado,p.nombre_proyecto ORDER BY b.clbod_obra_id,b.clbod_semana DESC,clbod_ano DESC");

		$segui_visit = DB::select("select u.name,clbod_semana||'-'||clbod_ano as fecha,count(clbod_semana)numero from abastecimiento.clbod b inner join seguridadapp.aplicacion_usuario u on u.id_aplicacion_usuario = CAST(b.clbod_create_user as integer) inner join ggo.ggo_proyecto p on trim(p.cod_proyecto) = b.clbod_obra_id where b.clbod_tipo = 2 group by u.name,clbod_semana,clbod_ano order by b.clbod_semana asc");

		$segui_visit_acu = DB::select("select u.name,count(clbod_semana)numero from abastecimiento.clbod b inner join seguridadapp.aplicacion_usuario u on u.id_aplicacion_usuario = CAST(b.clbod_create_user as integer) inner join ggo.ggo_proyecto p on trim(p.cod_proyecto) = b.clbod_obra_id where b.clbod_tipo = 2 group by u.name");
		//print(json_encode($tabla_visita));
        return view('reports',compact('año','historicoCheckList','week','reporte_seguimiento','historicoProy','historicoVisitaWeek','tabla_visita','segui_visit','segui_visit_acu'));
    }
}