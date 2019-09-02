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
                    <form id="form_bodega" method="POST">
                    <meta name="csrf-token" content="{{ csrf_token() }}" />
                        <header class="section-header">
                            <h3>Checklist de autocontrol en Almacén</h3>
                            <div class="row">
                                <div class="col-md-3">
                                    <small id="week" style="text-transform: uppercase;display: block;font-weight: bold;color:#e8000a;">semana de evaluación: {{ $week }}</small>
                                    <label id="fecha" style="color:#000">Fecha: {{ $now }}</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="pull-right" >
                                        <div style="position: relative;float: left;margin-right: 3px;">
                                            <small style="margin-top: .9em;text-transform: uppercase;display: block;font-weight: bold;"></small>
                                        </div>
                                        <a class="btn btn-sm btn-default" href="{{ route('principal') }}"><i class="glyphicon glyphicon-chevron-left"></i> Volver</a>
                                        <button type="submit" id="guardar_chckbdg" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-floppy-disk"></i> Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </header>
                        <br>    
                        <div class="form-grou row">
                            <div class="col col-md-3 col-lg-3 col-sm-12 col-xs-12">
                                <label id="creador">Creador: <b style="color:#000;text-transform: none;">{{ $email }}</b></label>
                            </div>
                        </div>
                        <br>
                        <div class="form-grou row">
                            <div class="col col-md-9 col-lg-9 col-sm-12 col-xs-12">
                                <label for="comboObra">Obra </label>
                                <select id="comboObra" required="true" class="form-control" name="comboObra" data-live-search="true">
                                    <option value="" selected="selected">Seleccione</option>
                                    @foreach($proyectos as $pry)
                                        @if(array_search(trim($pry->cod_proyecto), array_column($proyectos_realizados, 'clbod_obra_id'))=== false)
                                            <option value="{{ $pry->cod_proyecto }}">{{ $pry->id_unidad_negocio.' - '.$pry->nombre_proyecto }}</option>
                                        @endif
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
                                                    $count = count($tbl_p->hijo)+1;
                                                }else{
                                                    $count = count($tbl_p->hijo)+2;
                                                }
                                            ?>
                                            <td rowspan="<?php echo $count ?>" style="font-weight: bold">{{ $tbl_p->cabecera }}</td>
                                            @if(!empty($tbl_p->hijo))
                                            @foreach($tbl_p->hijo as $tbl_h)
                                            <tr>
                                                <td style="text-align: left;" ><b><?php echo $contador.'. ' ?></b>{{ $tbl_h->clbod_preguntas_nombre }}</td>
                                                <td>
                                                    <select data-id="<?php echo $tbl_h->clbod_preguntas_item_id ?>" name="rev[<?php echo $tbl_h->clbod_preguntas_item_id ?>]" id="{{ $tbl_h->clbod_preguntas_item_id }}" class="form-control id-question"  >
                                                        <option value="S" >SI</option>
                                                        <option value="N" >NO</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php $contador++; ?>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td style="text-align: left;" ></td>
                                                <td><input data-id="<?php echo $tbl_p->id_cabecera ?>" type="number" min="0" max="100" step=".01" name="rev[<?php echo $tbl_p->id_cabecera ?>]" id="{{ $tbl_p->id_cabecera }}" class="form-control req id-question"><small>Debe ingresar el porcentaje sin <b>%</b> y el separador decimal con <b>.</b></small></td>
                                            </tr>
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
<section>
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="modal_ajax" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document" style="overflow-y: initial !important; margin: 0px; left: 50%; top: 50%; transform: translate(-50%, -50%);">
          <div class="modal-content">
              <div class="modal-body" id="contenedor_validacion" style="max-height: 71vh;overflow-y: auto;overflow-x: hidden;">
              </div>            
          </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
$('#comboObra').selectpicker();

$(document).ready(function () {
    $(".preloader-wrapper").removeClass('active');
    $("#nav-ad").addClass('menu-active');
    $('#form_bodega').bind("submit",function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        <?php $perfil = Auth::user()->rol[0]->id_rol; ?>
        perfil = <?php echo $perfil ?>;
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        var count = 0;
        $('.id-question').each(function(){
            formData.append('id['+count++ +']', $(this).attr('data-id'));
        });
        $('#modal_ajax .modal-body').html('<div class="preloader text-center"><br><br><img class="center-block" src="https://www.gestionflesan.cl/controlflujo/images/grupo_flesan.png" style="width: 250px;"><br><br><p><img src="https://www.gestionflesan.cl/controlflujo/images/preloader_2019.gif" style="width: 25px;"><strong style="color: #adadad!important;font-size:13px;"> OBTENIENDO DATOS</strong></p></div>');

        $('#modal_ajax').modal('show', {backdrop: 'static', keyboard: true});
        $.ajax({
            url:'/verificateAlmacenWeek',
            data:formData,
            cache:false,
            contentType:false,
            processData:false,
            type: 'POST',
            datatype: 'JSON',
            success: function (response) {
                if(response.length == 0){
                    $.ajax({
                        url:'/savealmacen',
                        data:formData,
                        cache:false,
                        contentType:false,
                        processData:false,
                        type: 'POST',
                        datatype: 'JSON',
                        success: function (response) {
                            $.ajax({
                                url:'/verificateAlmacenWeekItem',
                                data:formData,
                                cache:false,
                                contentType:false,
                                processData:false,
                                type: 'POST',
                                datatype: 'JSON',
                                success: function (response) {
                                    if(response.length == 0){
                                        $.ajax({
                                            url:'/savealmacenItem',
                                            data:formData,
                                            cache:false,
                                            contentType:false,
                                            processData:false,
                                            type: 'POST',
                                            datatype: 'JSON',
                                            success: function (response) {
                                                $('#modal_ajax').modal('hide');
                                                location.href="/principal";
                                                swal('¡Exito!','Se registro el ChekList Almacén de la semana '+response.clbod_item_semana,'success');
                                            },error: function(jqXHR, text, error){
                                                $('#modal_ajax').modal('hide');
                                                swal('Error!','No se pudo registrar ningun ChecList Almacén para esta semana para el proyecto seleccionado.','error');
                                            }
                                        });
                                    }else{
                                        $('#modal_ajax').modal('hide');
                                        swal('Error!','Ya existe Checklist Almacén para el Proyecto seleccionado.','error');
                                        $('#comboObra').focus();
                                    }
                                },error: function(jqXHR, text, error){
                                    $('#modal_ajax').modal('hide');
                                    swal('Error!','No se pudo registrar ningun CheckList Almacén para esta semana para el proyecto seleccionado.','error');
                                }
                            });
                        },error: function(jqXHR, text, error){
                            $('#modal_ajax').modal('hide');
                            swal('Error!','No se pudo registrar ningun ChecList Almacén para esta semana para el proyecto seleccionado.','error');
                        }
                    });
                }else{
                    $('#modal_ajax').modal('hide');
                    swal('Error!','Ya existe Checklist Almacén para el proyecto seleccionado.','error');
                    $('#comboObra').focus();
                }
            },error: function(jqXHR, text, error){
                $('#modal_ajax').modal('hide');
                swal('Error!','No se pudo registrar ningun ChecList Almacén para esta semana para el proyecto seleccionado.','error');
            }
        });
    });
}); 

</script>
@endsection