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
                            <h3>Checklist de autocontrol en Almacén</h3>
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
                                        <a class="btn btn-sm btn-default" href="{{ route('principal') }}"><i class="glyphicon glyphicon-chevron-left"></i> Volver</a>
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
                                    if($checklist[0]->clbod_cumplimiento <= 100 && $checklist[0]->clbod_cumplimiento >= 90){
                                        $color = 'btn-flesan-table-ok';
                                        $textcolor = 'text-white';
                                    }else if($checklist[0]->clbod_cumplimiento < 90 && $checklist[0]->clbod_cumplimiento >= 66){
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
                                                    $count = count($tbl_p->hijo)+1;
                                                    $ancho = "rowspan = $count";
                                                }else{
                                                    $count = count($tbl_p->hijo)+2;
                                                    $ancho = "colspan = $count";
                                                }
                                            ?>
                                            <td <?php echo $ancho ?> style="font-weight: bold">{{ $tbl_p->cabecera }}</td>
                                            @if(!empty($tbl_p->hijo))
                                            @foreach($tbl_p->hijo as $tbl_h)
                                            <tr>
                                                <td style="text-align: left;" ><b><?php echo $contador.'. ' ?></b>{{ $tbl_h->clbod_preguntas_nombre }}</td>
                                                <td>
                                                    <select data-id="<?php echo $tbl_h->clbod_item_preguntas_id ?>"  name="rev[<?php echo $tbl_h->clbod_item_preguntas_id ?>]" <?php if(Auth::user()->rol[0]->id_rol != 10 || (!empty($checklist[0]->clbod_validate_user))) echo 'disabled readonly="true"' ?> id="{{ $tbl_h->clbod_item_preguntas_id }}" class="form-control id-question select-preg"  >
                                                        <option value="S" <?php echo ($tbl_h->clbod_item_cumple == 'S')?'selected':'' ?>>SI</option>
                                                        <option value="N" <?php echo ($tbl_h->clbod_item_cumple == 'N')?'selected':'' ?>>NO</option>
                                                        <option value="0" <?php echo ($tbl_h->clbod_item_cumple == '0')?'selected':'' ?>>N/A</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php $contador++; ?>
                                            @endforeach
                                            @else
                                                <td><input data-id="<?php echo $tbl_p->id_cabecera ?>" readonly="true" type="number" min="1" max="5"  name="rev[<?php echo $tbl_p->id_cabecera ?>]" id="{{ $tbl_p->id_cabecera }}"  value="{{ $tbl_p->value }}" class="form-control req id-question select-input"></td>
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

$(document).ready(function () {
    $(".preloader-wrapper").removeClass('active');
    $("#nav-ad").addClass('menu-active');
    $(".select-preg").change(function() {
    var total_sl = $('.select-preg').length;
    var total_sl_s = 0;
    var total_sl_n = 0;
    $(".select-preg").each(function(){
        if ($('option:selected',this).val() == 'S') {
            total_sl_s++;
        }else if ($('option:selected',this).val() == 'N'){
            total_sl_n++;
        }
    })
    var ponderado = 0;
    var porcen_tot = total_sl_s * 100/total_sl;
    if(porcen_tot <= 20 && porcen_tot >=0){
        ponderado = 1;
    }else if(porcen_tot <= 50 && porcen_tot >20){
        ponderado = 2;
    }else if(porcen_tot <= 70 && porcen_tot >50){
        ponderado = 3;
    }else if(porcen_tot <= 90 && porcen_tot >70){
        ponderado = 4;
    }else if(porcen_tot <= 1000 && porcen_tot >90){
        ponderado = 5;
    }
    var ubicacion_total = $('.select-input').attr('id');
        $('#'+ubicacion_total).val(ponderado);
    });
    $('#form_bodega_edit').bind("submit",function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        var count = 0;
        $('.id-question').each(function(){
            formData.append('id['+count++ +']', $(this).attr('data-id'));
        });
        $('#modal_ajax .modal-body').html('<div class="preloader text-center"><br><br><img class="center-block" src="https://www.gestionflesan.cl/controlflujo/images/grupo_flesan.png" style="width: 250px;"><br><br><p><img src="https://www.gestionflesan.cl/controlflujo/images/preloader_2019.gif" style="width: 25px;"><strong style="color: #adadad!important;font-size:13px;"> OBTENIENDO DATOS</strong></p></div>');

        $('#modal_ajax').modal('show', {backdrop: 'static', keyboard: true});
        @if(empty($checklist[0]->clbod_validate_user))
        $.ajax({
            url:'/validateAlmacenWeekItem',
            data:formData,
            cache:false,
            contentType:false,
            processData:false,
            type: 'POST',
            datatype: 'JSON',
            success: function (response) {
                $('#modal_ajax').modal('hide');
                location.reload();
                swal('¡Exito!','Se valido el ChekList Almacén de la semana '+response.clbod_semana,'success');
            },error: function(jqXHR, text, error){
                $('#modal_ajax').modal('hide');
                swal('Error!','No se pudo validar ningún ChecList Almacén para esta semana para el proyecto seleccionado.','error');
            }
        });
        @else
            $('#modal_ajax').modal('hide');
            swal('Error!','El ChecList Almacén de esta semana para el proyecto seleccionado ya se encuentra validado.','error');
            location.reload();
        @endif
    });
});
</script>
@endsection
