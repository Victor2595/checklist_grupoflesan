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
                    <form id="change_semana" class="hidden-xs " method="POST">
                    <meta name="csrf-token" content="{{ csrf_token() }}" />
                        <header class="section-header">
                            <h3>Checklist de autocontrol en Visita</h3>
                            <div class="row">
                                <div class="col-md-3">
                                    <small style="text-transform: uppercase;display: block;font-weight: bold;color:#e8000a;">semana de evaluación: {{ $week }}</small>
                                    <label style="color:#000">Fecha: {{ $now }}</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="pull-right" >
                                        <div style="position: relative;float: left;margin-right: 3px;">
                                            <small style="margin-top: .9em;text-transform: uppercase;display: block;font-weight: bold;"></small>
                                        </div>
                                        <a class="btn btn-sm btn-default" href="javascript: history.go(-1)"><i class="glyphicon glyphicon-chevron-left"></i> Volver</a>
                                        <button type="submit" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-floppy-disk"></i> Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </header>
                        <br>    
                        <div class="form-grou row">
                            <div class="col col-md-3 col-lg-3 col-sm-12 col-xs-12">
                                <label >Creador: <b style="color:#000;text-transform: none;">{{ $email }}</b></label>
                            </div>
                        </div>
                        <br>
                        <div class="form-grou row">
                            <div class="col col-md-9 col-lg-9 col-sm-12 col-xs-12">
                                <label for="comboObra">Obra </label>
                                <select id="comboObra" required="true" class="form-control" name="comboObra" data-live-search="true">
                                    <option value="" selected="selected">Seleccione</option>
                                    @foreach($proyectos as $pry)
                                    <option value="{{ $pry->id_proyecto }}">{{ $pry->cod_empresa.' - '.$pry->nombre_proyecto }}</option>
                                    @endforeach
                                </select>
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
                                                    $variable = count($tbl_p->hijo)+1;
                                                    $count = 'colspan="'.$variable.'"';
                                                    $numero='<b>'.$contador.'. </b>';
                                                }
                                            ?>
                                            <?php 
                                                if(count($tbl_p->hijo)!=0){
                                                    $td = '';
                                                    $style = '';
                                                }else{
                                                    $td = '<td></td>';
                                                    $style = 'text-align:left';
                                                }
                                            ?>
                                            <?php echo $td; ?>
                                            <td <?php echo $count ?> style="font-weight: bold;<?php echo $style; ?>"><?php echo $numero; ?>{{ $tbl_p->cabecera }}</td>
                                            
                                            @if(!empty($tbl_p->hijo))
                                            @foreach($tbl_p->hijo as $tbl_h)
                                            <tr>
                                                <td style="text-align: left;" ><b><?php echo $contador.'. ' ?></b>{{ $tbl_h->clbod_preguntas_nombre }}</td>
                                                <td>
                                                    <select name="rev[<?php echo $contador ?>]" class="form-control" data-id="c_<?php echo $contador ?>" >
                                                        <option value="S" >SI</option>
                                                        <option value="N" >NO</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php $contador++; ?>
                                            @endforeach
                                            @else
                                                <td><input type="number" name="rev[<?php echo $contador; ?>]" class="form-control req" data-id="c_23" value="" min="1" max="5"></td>
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
$('#comboObra').selectpicker();

$(document).ready(function () {
    $('#change_semana').bind("submit",function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url:'/verificateVisitaWeek',
            data:formData,
            cache:false,
            contentType:false,
            processData:false,
            type: 'POST',
            datatype: 'JSON',
            success: function (response) {
                if(response.length == 0){
                    $.ajax({
                        url:'/savevisita',
                        data:formData,
                        cache:false,
                        contentType:false,
                        processData:false,
                        type: 'POST',
                        datatype: 'JSON',
                        success: function (response) {
                            location.href="/principal";
                            swal('¡Exito!','Se registro el ChekList Visita de la semana '+response.semana,'success');
                        },error: function(jqXHR, text, error){
                            swal('Error!','No se pudo registrar ningun ChecList Visita para esta semana para el proyecto seleccionado.','error');
                        }
                    });
                }else{
                    swal('Error!','Ya existe Checklist Visita para el proyecto seleccionado.','error');
                    $('#comboObra').focus();
                }
            },error: function(jqXHR, text, error){
                //swal('Error!','No se pudo registrar ningun ChecList para esta semana para el proyecto seleccionado.','error');
            }
        });

    });
}); 
</script>
@endsection