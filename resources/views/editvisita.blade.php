@extends('layouts.template')

@section('style')
        <link rel="stylesheet" type="text/css" href="{{asset('js/datatables/css/jquery.dataTables.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('js/datatables/css/buttons.dataTables.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('js/datatables/css/responsive.dataTables.min.css')}}">
@endsection

@section('content')
   <br><br>
</section>
<section class="content" style="">
    <div class="col-xs-12">
        <div class="row" style="display: block">
            <section id="contact" class="section-bg wow">
                <div class="container">
                    <form id="form_bodega_edit" method="POST">
                    <meta name="csrf-token" content="{{ csrf_token() }}" />
                        <header class="section-header">
                            <h3>Checklist de autocontrol en Visita</h3>
                            <div class="row">
                                <div class="col-md-3">
                                    <small id="week" style="text-transform: uppercase;display: block;font-weight: bold;color:#e8000a;">semana de evaluación: {{ $semana }}</small>
                                    <input type="hidden" id="week" name="week" value="{{ $semana }}">
                                    <?php 
                                        $date = new DateTime($checklist[0]->clbod_create_date);
                                        $year = date('Y');
                                    ?>
                                    <input type="hidden" id="year" name="year" value="<?php echo $year; ?>">
                                    <label id="fecha" style="color:#000">Fecha: {{ $checklist[0]->clbod_create_date }}</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="pull-right" >
                                        <div style="position: relative;float: left;margin-right: 3px;">
                                            <small style="margin-top: .9em;text-transform: uppercase;display: block;font-weight: bold;"></small>
                                        </div>
                                        <a class="btn btn-sm btn-secondary" href="{{ route('principal') }}"><i class="glyphicon glyphicon-chevron-left"></i> Volver</a>
                                        @if(Auth::user()->rol[0]->id_rol ==10)
                                            @if((empty($checklist[0]->clbod_validate_user)))
                                            <button type="submit" id="guardar_chckbdg" class="btn btn-sm btn-default"><i class="far fa-save"></i> Validar</button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </header>
                        <br>    
                        <div class="form-grou row">
                            <div class="col col-md-3 col-lg-3 col-sm-12 col-xs-12">
                                <label id="creador">Creador: <b style="color:#000;text-transform: none;">{{ $checklist[0]->clbod_create_user }}</b></label>
                            </div>
                        </div>
                        <br>
                        <div class="form-grou row">
                            <div class="col col-md-9 col-lg-9 col-sm-12 col-xs-12">
                                <label for="inputOb">Obra: </label>
                                <span>
                                    <input type="text" name="obra_id" id="obra_id" class="sr-only" value="{{ $proyectos[0]->cod_proyecto }}">
                                    <input type="hidden"  id="obra_name" name="obra_name" value="{{ $proyectos[0]->id_unidad_negocio }}">
                                    <br><b><strong  style="color:#000;" id="obra_nombre">{{ strtoupper($proyectos[0]->id_unidad_negocio) }}</strong></b>
                                </span>
                            </div>
                            <div class="form-group">
                                <label>Cumplimiento:</label>
                                <br>
                                <?php 
                                    if($checklist[0]->clbod_cumplimiento <= 100 && $checklist[0]->clbod_cumplimiento >= 93){
                                        $color = 'btn-flesan-table-ok';
                                        $textcolor = 'text-white';
                                    }else if($checklist[0]->clbod_cumplimiento <= 92 && $checklist[0]->clbod_cumplimiento >= 71){
                                        $color = 'btn-flesan-table-warning';
                                        $textcolor = 'text-black';
                                    }else{
                                        $color = 'btn-flesan-table';
                                        $textcolor = 'text-white';
                                    }
                                ?>    
                                <span class="btn btn-sm center-block <?php echo $color; ?> text-bold <?php echo $textcolor ?> font-weight-bold" style="padding-left: 6px">{{ round($checklist[0]->clbod_cumplimiento,0) }}%</span>
                            </div>
                        </div>
                        <br>
                        <?php 
                            if(!empty($checklist[0]->clbod_validate_user)){
                                $estado_validacion = 'VALIDADO';
                                $color = 'bg-success';
                            }else{
                                $estado_validacion = 'NO VALIDADO';
                                $color = 'bg-danger';
                            }
                        ?>
                        <div class="form-grou row">
                            <div class="col col-md-3 col-lg-3 col-sm-12 col-xs-12">
                                <label id="creador">Estado: </label>
                                <span class="label <?php echo $color ?> text-white font-weight-bold" style="font-size: 100%;"><?php echo $estado_validacion; ?></span>
                            </div>
                        </div>
                        <br>
                        <div class="form-grou row">
                            <div class="col col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <table id="tabla_data" class="table table-bordered table-striped dataTable no-footer dtr-inline" data-toggle="table">
                                    <thead class="thead-dark bg-flesan-th">
                                        <th class="text-center">Tópico</th>
                                        <th>Revisión</th>
                                        <th>Cumplimiento</th>                               
                                    </thead>
                                    <tbody>
                                        @if(!empty($arreglo))
                                        <?php $contador=1 ?>
                                        @foreach($arreglo as $tbl_p)
                                        <tr><td colspan="4" style="background-color: #dd4b39 !important; padding: 1px 0;"></td></tr>
                                        <tr class="{{ $tbl_p->id_cabecera }}">
                                            <?php 
                                                $count ="";
                                                if(count($tbl_p->hijo)!=0){
                                                    $variable = count($tbl_p->hijo)+1;
                                                    $count = 'rowspan="'.$variable.'"';
                                                    $numero='<b></b>';
                                                }else{
                                                    $variable = count($tbl_p->hijo)+2;
                                                    $count = 'colspan="'.$variable.'"';
                                                    $numero='<b></b>';
                                                }
                                            ?>
                                            
                                            <td <?php echo $count ?> style="font-weight: bold;"><?php echo $numero; ?>{{ $tbl_p->cabecera }}</td>
                                            
                                            @if(!empty($tbl_p->hijo))
                                            @foreach($tbl_p->hijo as $tbl_h)
                                            <tr>
                                                <td style="text-align: left;" ><b><?php echo $contador.'. ' ?></b>{{ $tbl_h->clbod_preguntas_nombre }}</td>
                                                <td>
                                                    <select name="rev[<?php echo $tbl_h->clbod_item_preguntas_id ?>]" disabled="true" id="{{ $tbl_h->clbod_item_preguntas_id }}" class="form-control id-question" data-id="<?php echo $tbl_h->clbod_item_preguntas_id ?>" >
                                                        <option value="S" <?php echo ($tbl_h->clbod_item_cumple == 'S')?'selected':'' ?>>SI</option>
                                                        <option value="N" <?php echo ($tbl_h->clbod_item_cumple == 'N')?'selected':'' ?>>NO</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php $contador++; ?>
                                            @endforeach
                                            @else
                                                <td><input type="number" name="rev[<?php echo $tbl_p->id_cabecera ?>]" id="{{ $tbl_p->id_cabecera }}" disabled="true" required="true" class="form-control req id-question" data-id="<?php echo $tbl_p->id_cabecera ?>" value="{{ $tbl_p->value }}" min="1" max="5"></td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="4">No hay datos disponibles en la tabla.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>                
                            </div>
                        </div>
                    </form>    
                </div>      
            </section>   
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
$(document).ready(function () {
    $(".preloader-wrapper").removeClass('active');
    $("#nav-ad").addClass('menu-active');
    $('#form_bodega_edit').bind("submit",function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $.ajax({
            url:'/validateVisitaWeekItem',
            data:formData,
            cache:false,
            contentType:false,
            processData:false,
            type: 'POST',
            datatype: 'JSON',
            success: function (response) {
                swal('¡Exito!','Se valido el ChekList Visita de la semana '+response.clbod_semana,'success');
                location.reload();
            },error: function(jqXHR, text, error){
                swal('Error!','No se pudo validar ningún ChecList Visita para esta semana para el proyecto seleccionado.','error');
            }
        });
    });
}); 
</script>
@endsection
