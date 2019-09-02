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
                    <header class="section-header">
                        <h3>Checklist semanal - logística</h3>
                        <div class="row">
                            <div class="col-md-3">
                                <small style="text-transform: uppercase;display: block;font-weight: bold;color:#e8000a;">semana de evaluación: {{ $week }}</small>
                            </div>
                            <div class="col-md-9">
                                <div class="pull-right" >
                                    <!--<div style="position: relative;float: left;margin-right: 3px;">
                                        <small style="margin-top: .9em;text-transform: uppercase;display: block;font-weight: bold;">{{ $rol }}</small>
                                    </div>-->
                                    @if($perfil == 11)
                                    <a class="btn btn-sm btn-default" href="{{ route('almacen') }}"><i class="glyphicon glyphicon-plus"></i> Almacén</a>
                                    @endif
                                    @if($perfil == 12)
                                    <a class="btn btn-sm btn-default" href="{{ route('visita') }}"><i class="glyphicon glyphicon-plus"></i> Visita</a>
                                    @endif
                                                 
                                </div>
                            </div>
                        </div>
                    </header>
                    <br>    
                    <form id="change_semana" class="hidden-xs " method="POST" style="width: 400px;">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="form-grou row">
                            <div class="col col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                 <select id="combobox_tipo" class="form-control" name="tipo_check" data-live-search="true">
                                    <option class="<?php echo (Auth::user()->rol[0]->id_rol == 12)?'hidden':'' ?>" value="1" <?php if(Auth::user()->rol[0]->id_rol == 10){ echo 'selected';}elseif (Auth::user()->rol[0]->id_rol == 11) {echo 'selected';}?>>Checklist de Almacén</option>
                                    <option class="<?php echo (Auth::user()->rol[0]->id_rol == 11)?'hidden':'' ?>" value="2" <?php if (Auth::user()->rol[0]->id_rol == 12) {echo 'selected';}?>>Checklist de Visita</option>
                                </select>
                            </div>
                            <div class="col col-md-6 col-lg-6 col-sm-12 col-xs-12 input-group input-group-sm">
                                <select id="combobox_semana" class="form-control" name="ano_semana" data-live-search="true">
                                    @if(!empty($historicoCheckList))
                                        <option value="{{ $año.'_'.$week }}">{{ $año.' Semana '.$week }}</option>
                                        @foreach($historicoCheckList as $historico)
                                            @if($historico->clbod_semana <> $week)
                                            <option value="{{ $historico->clbod_ano.'_'.$historico->clbod_semana }}">{{ $historico->clbod_ano.' Semana '.$historico->clbod_semana }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        <option value="{{ $año.'_'.$week }}">{{ $año.' Semana '.$week }}</option>
                                    @endif                                       
                                </select>
                                <span class="input-group-btn"><button type="button" id="filter_cl" alt="filtrar" title="filtrar" class="btn btn-default btn-flesan"><i class="glyphicon glyphicon-search"></i></button></span>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div id="cont_table">
                        @if(!empty($tabla_bodega) || Auth::user()->rol[0]->id_rol == 10)
                        <table id="dataTableCheck" class="table table-bordered table-striped dataTable no-footer dtr-inline">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" class="text-center">Tipo</th>
                                    <th scope="col" class="text-center" width="8%">Unidad</th> 
                                    <th scope="col" class="text-center">Obra</th>     
                                    <th scope="col" class="text-center">Creador</th>
                                    <th scope="col" class="text-center" width="8%">Fecha</th>                                      
                                    <th scope="col" class="text-center">Validador</th>
                                    <th scope="col" class="text-center">Fecha</th>  
                                    <th scope="col" class="text-center" width="8%">Porcentaje</th>
                                </tr>                                       
                            </thead>
                            <tbody> 
                                @foreach($tabla_bodega as $tabla)
                                <tr class="{{ $tabla->clbod_obra_id }}">      
                                    <td class="text-left">
                                        <?php 
                                            echo ($tabla->clbod_tipo == 1)?'Checklist de Almacén':'Checklist de Visita'; 
                                        ?>
                                        </td>
                                    <td class="text-center">{{ $tabla->unidad_negocio }}</td>
                                    <td >                                                                      
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong class="text-left">{{ $tabla->obra }}</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong class="text-right"><?php echo 'Semana: '.$week; ?></strong>
                                                    <small>
                                                    <?php
                                                        $timestamp=mktime(0, 0, 0, 1, 1, $año);
                                                        $timestamp+=$week*7*24*60*60;
                                                        $ultimoDia=$timestamp-date("w", mktime(0, 0, 0, 1, 1, $año))*24*60*60;
                                                        $primerDia=$ultimoDia-86400*(date('N',$ultimoDia)-1);
                                                        $ultimoDia=date("d-m-Y",$ultimoDia);
                                                        $primerDia=date("d-m-Y",$primerDia);
                                                        echo ' ('.$primerDia.' a '.$ultimoDia.')';
                                                    ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-left">{{ $tabla->clbod_create_user }}</td>
                                    <td class="text-center">
                                    <?php 
                                        $fecha =  date_create($tabla->clbod_create_date); 
                                        echo date_format($fecha, 'd/m/Y'); 
                                    ?>
                                    </td>
                                    <td class="text-center">                                
                                        @if($perfil <> 10)
                                            @if($tabla->clbod_tipo == 1)
                                                <a class="btn btn-sm mr-1 text-left bg-info" href="{{ URL::route('editOldAlmacen',['id' => $tabla->clbod_obra_id,'week'=> $week,'año'=> $año]) }}" title="Ver Checklist Semanal"><i class="glyphicon glyphicon-eye-open"></i></a>
                                            @else
                                                <a class="btn btn-sm mr-1 text-left bg-info" href="{{ URL::route('editOldVisita',['id' => $tabla->clbod_obra_id,'week'=> $week,'año'=> $año]) }}" title="Ver Checklist Semanal"><i class="glyphicon glyphicon-eye-open"></i></a>
                                            @endif
                                        @elseif($perfil == 10)
                                            @if($tabla->clbod_tipo == 1)
                                            <a class="btn btn-sm text-left <?php echo (isset($tabla->clbod_validate_user))?'btn-success':'btn-danger';?> mr-1" href="{{ URL::route('editOldAlmacen',['id' => $tabla->clbod_obra_id,'week'=> $week,'año'=> $año]) }}" title="<?php echo (isset($tabla->clbod_validate_user))?'Checklist Semanal Validado':'Validar Checklist Semanal';?>"><i class="glyphicon glyphicon-<?php echo (isset($tabla->clbod_validate_user))?'ok':'remove-sign'; ?>"></i></a>
                                            @else
                                            <a class="btn btn-sm text-left <?php echo (isset($tabla->clbod_validate_user))?'btn-success':'btn-danger';?> mr-1" href="{{ URL::route('editOldVisita',['id' => $tabla->clbod_obra_id,'week'=> $week,'año'=> $año]) }}" title="<?php echo (isset($tabla->clbod_validate_user))?'Checklist Semanal Validado':'Validar Checklist Semanal';?>"><i class="glyphicon glyphicon-<?php echo (isset($tabla->clbod_validate_user))?'ok':'remove-sign'; ?>"></i></a>
                                            @endif    
                                        @endif
                                        <?php echo $tabla->clbod_validate_user; ?>   
                                    </td>
                                    <?php  
                                            if(!empty($tabla->clbod_validate_date)){
                                                $fecha_v =  date_create($tabla->clbod_validate_date); 
                                                $fecha_v = date_format($fecha_v, 'd/m/Y');
                                                $color = '';
                                            }else{
                                                $fecha_v = 'No Validado';
                                                $color = 'style="font-weight:bold;"';
                                            }
                                        ?>
                                    <td class="text-center" <?php echo $color; ?>><?php echo $fecha_v;?></td>
                                    <?php 
                                        if($tabla->clbod_cumplimiento <= 100 && $tabla->clbod_cumplimiento >= 93){
                                            $color = 'btn-flesan-table-ok';
                                            $textcolor = 'text-white';
                                        }else if($tabla->clbod_cumplimiento <= 92 && $tabla->clbod_cumplimiento >= 71){
                                            $color = 'btn-flesan-table-warning';
                                            $textcolor = 'text-black';
                                        } else {
                                            $color = 'btn-flesan-table';
                                            $textcolor = 'text-white';
                                        }
                                    ?>
                                    <td class="text-center">
                                        <a class="btn btn-sm center-block <?php echo $color ?>" style="padding-left: 8px" role="button" data-toggle="tooltip" title="{{ $tabla->clbod_cumplimiento }}%" data-placement="top"><span class="text-bold <?php echo $textcolor ?>">{{ $tabla->clbod_cumplimiento }}%</span></a>
                                    </td>       
                                </tr>
                                @endforeach                                
                            </tbody>
                        </table>
                        @elseif(Auth::user()->rol[0]->id_rol != 10 && empty($tabla_bodega))
                            <div class="alert alert-danger" id="mensaje_error"><h4><i class="icon fa fa-ban"></i> Advertencia!</h4>¡No existen checklist creados para {{ $año }} Semana {{ $week }}!</div>
                        @endif
                    </div>
                </div>      
            </section>   
        </div>
    </div>
</section>
@endsection

@section('script')
<script type="text/javascript" src="{{asset('js/datatables/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/datatables/js/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/datatables/js/buttons.flash.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/datatables/js/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/datatables/js/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/datatables/js/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{asset('js/datatables/js/buttons.html5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/datatables/js/buttons.print.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/datatables/js/dataTables.responsive.min.js')}}"></script>
<script>
    $('select').selectpicker();
    $(document).ready(function() {
        $('select').selectpicker();
        $(".preloader-wrapper").removeClass('active');
        $("#nav-ad").addClass('menu-active');
        $("#nav-guser").removeClass('menu-active');
        $('#dataTableCheck').removeAttr('style');
        $("#dataTableCheck").DataTable({
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true,
            "order"       : [[1, "desc"]],
            "pageLength"  : 50,
            'responsive'  : true,
            'destroy'     : true
        });
    });

    $("#filter_cl").click(function() {
        $('#cont_table').html('<div class="preloader" style="text-align: center;"><br><br><img class="center-block" src="https://www.gestionflesan.cl/controlflujo/images/grupo_flesan.png" style="width: 250px;"><br><p><img class="center-block" src="https://www.gestionflesan.cl/controlflujo/images/preloader_2019.gif" style="width: 25px;"><strong  style="color: #adadad!important;font-size:13px;text-align:center"> OBTENIENDO DATOS</strong></p></div>');
        $.ajax({
            url:'/searchListSemanal',
            data:{"combobox_tipo":$("#combobox_tipo").val(),"_token": $('meta[name="csrf-token"]').attr('content'),"combobox_semana":$("#combobox_semana").val()},
            type: 'POST',
            datatype: 'JSON',
            success: function (response) {
                if(response.length >= 1){
                    $('#cont_table').html('<table id="dataTableCheck" class="table table-bordered table-striped dataTable no-footer dtr-inline"></table>');
                    var dataSet = [];
                    $.each(response, function(i,item){
                        var dataTD = [];
                        if(item.clbod_tipo == 1){
                            tipo = 'Checklist de Almacén';
                        }else{
                            tipo = 'Checklist de Visita';
                        }

                        var year = item.clbod_ano;
                        var week = item.clbod_semana;
                        var primer = new Date(year, 0, (week - 1) * 7);
                        mes_primer =primer.getMonth()+1;
                        if(mes_primer < 10){
                            primerDay = primer.getDate()+'-0'+mes_primer+'-'+primer.getFullYear();
                        }else{
                            primerDay = primer.getDate()+'-'+mes_primer+'-'+primer.getFullYear();
                        }

                        var ultimo = new Date(year, 0, (week - 1) * 7 + 6);
                        mes_ultimo =ultimo.getMonth()+1;
                        if(mes_ultimo < 10){
                            ultimoDay = ultimo.getDate()+'-0'+mes_ultimo+'-'+ultimo.getFullYear();
                        }else{
                            ultimoDay = ultimo.getDate()+'-'+mes_ultimo+'-'+ultimo.getFullYear();
                        }

                        fecha_c = (item.clbod_create_date).replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');

                        perfil = <?php echo $perfil ?>;
                        if(perfil != 10){
                            if (item.clbod_validate_user != null){
                                user = item.clbod_validate_user;
                            }else{
                                user = '';
                            }
                            if(item.clbod_tipo == 1){
                                url = 'http://127.0.0.1:8000/editOldAlmacen/'+item.clbod_obra_id+'?week='+item.clbod_semana+'&&año='+item.clbod_ano ;
                            }else{
                                url = 'http://127.0.0.1:8000/editOldVisita/'+item.clbod_obra_id+'?week='+item.clbod_semana+'&&año='+item.clbod_ano;
                            }
                            etiqueta = '<a class="btn btn-sm mr-1 text-left bg-info" href="'+url+'" title="Ver Checklist Semanal"><i class="glyphicon glyphicon-eye-open"></i></a>'+user;
                        }else if(perfil == 10){
                            if (item.clbod_validate_user != null){
                                clas = 'btn-success';
                                title = 'Checklist Semanal Validado';
                                icon = 'ok';
                                user = item.clbod_validate_user;
                            }else{
                                clas = 'btn-danger';
                                title = 'Validar Checklist Semanal';
                                icon = 'remove-sign';
                                user = '';
                            }
                            if(item.clbod_tipo == 1){
                                url = 'http://127.0.0.1:8000/editOldAlmacen/'+item.clbod_obra_id+'?week='+item.clbod_semana+'&&año='+item.clbod_ano ;
                            }else{
                                url = 'http://127.0.0.1:8000/editOldVisita/'+item.clbod_obra_id+'?week='+item.clbod_semana+'&&año='+item.clbod_ano;
                            }

                            etiqueta = '<a class="btn btn-sm text-left '+clas+' mr-1" href="'+url+'" title="'+title+'"><i class="glyphicon glyphicon-'+icon+'"></i></a>'+user;
                        }

                        if(item.clbod_validate_date != null) {
                            fecha_valida = (item.clbod_validate_date).replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
                        }else{
                            fecha_valida = '<b>No Validado</b>'
                        }

                        if(item.clbod_cumplimiento <= 100 && item.clbod_cumplimiento >= 93){
                            color = 'btn-flesan-table-ok';
                            textcolor = 'text-white';
                        }else if(item.clbod_cumplimiento <= 92 && item.clbod_cumplimiento >= 71){
                            color = 'btn-flesan-table-warning';
                            textcolor = 'text-black';
                        }else{
                            color = 'btn-flesan-table';
                            textcolor = 'text-white';
                        }

                        if(item.clbod_cumplimiento == null){
                            item.clbod_cumplimiento = '%';
                        }else{
                            item.clbod_cumplimiento = item.clbod_cumplimiento+'%';
                        }

                        dataTD.push(tipo);
                        dataTD.push(item.unidad_negocio);
                        dataTD.push('<div class="col-md-12"><div class="row"><div class="col-md-6"><strong class="text-left">'+item.obra+'</strong></div><div class="col-md-6"><strong class="text-right">Semana: '+item.clbod_semana+'</strong><small> ('+primerDay+' a '+ultimoDay+')</small></div></div></div>');
                        dataTD.push(item.clbod_create_user);
                        dataTD.push(fecha_c);
                        dataTD.push(etiqueta);
                        dataTD.push(fecha_valida);
                        dataTD.push('<a class="btn btn-sm center-block '+color+'" style="padding-left: 8px" role="button" data-toggle="tooltip" title="'+item.clbod_cumplimiento+'" data-placement="top"><span class="text-bold '+textcolor +'">'+item.clbod_cumplimiento+'</span></a>');
                        dataSet.push(dataTD);
                    });

                    $datatable = $('#dataTableCheck').DataTable({
                        destroy     : true,
                        paging      : true,
                        lengthChange: true,
                        searching   : true,
                        ordering    : true,
                        info        : true,
                        autoWidth   : true,
                        order       : [[1, "desc"]],
                        pageLength  : 50,
                        responsive  : false,
                        data        : dataSet,
                        columns     : [{ "title": 'Tipo', "className":"text-center" },{ "title": 'Unidad', "className":"text-center", "width":"8%" },{ "title": 'Obra', "className":"text-center" },{ "title": 'Creador', "className":"text-center" },{ "title": 'Fecha', "className":"text-center","width":"8%"},{ "title": 'Validador', "className":"text-center"},{ "title": 'Fecha', "className":"text-center"},{ "title": 'Porcentaje', "className":"text-center","width":"8%"}]
                     });

                    $datatable
                        .tables()
                        .header()
                        .to$()
                        .addClass('thead-dark');

                    $datatable
                        .tables()
                        .header()
                        .to$()
                        .addClass('text-center');
                    

                }else if(response.length == 0){
                    perfil = <?php echo $perfil ?>;
                    if(perfil == 10){
                        $('#cont_table').html('<table id="dataTableCheck" class="table table-bordered table-striped dataTable no-footer dtr-inline"></table>');
                                    
                                        $datatable = $('#dataTableCheck').DataTable({
                                            destroy     : true,
                                            paging      : true,
                                            lengthChange: true,
                                            searching   : true,
                                            ordering    : true,
                                            info        : true,
                                            autoWidth   : true,
                                            order       : [[1, "desc"]],
                                            pageLength  : 50,
                                            responsive  : true,
                                            columns     : [{ "title": 'Tipo', "className":"text-center" },{ "title": 'Unidad', "className":"text-center", "width":"8%" },{ "title": 'Obra', "className":"text-center" },{ "title": 'Creador', "className":"text-center" },{ "title": 'Fecha', "className":"text-center","width":"8%"},{ "title": 'Validador', "className":"text-center"},{ "title": 'Fecha', "className":"text-center"},{ "title": 'Porcentaje', "className":"text-center","width":"8%"}]
                                         });
                    
                                        $datatable
                                            .tables()
                                            .header()
                                            .to$()
                                            .addClass('thead-dark');
                    }else{
                        $('#cont_table').html('<div class="alert alert-danger" id="mensaje_error"><h4><i class="icon fa fa-ban"></i> Advertencia!</h4>¡No existen checklist creados para la fecha Seleccionada!</div>');

                    }
                }
            },
            error: function (response) {
            }
        });
    });
</script>
@endsection