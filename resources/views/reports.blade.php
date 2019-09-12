@extends('layouts.template')

@section('style')
        <style rel="stylesheet">
            @import url(https://fonts.googleapis.com/css?family=Roboto);

            body {
              font-family: Roboto, sans-serif;
            }

            
        </style>
@endsection

@section('content')
<?php 
  if(!empty($reporte_seguimiento)){
    foreach ($reporte_seguimiento as $uf) { 
      $Coleccion_1[] = json_encode(array( 'SEMANA' => $uf->semana, 'ALMACEN' => $uf->cumplimiento_almacen, 'VISITA' => $uf->cumplimiento_visita, 'PROYECTO' => $uf->nom_cod_proyecto, 'CODIGO' => $uf->cod_proyecto, 'NOMBRE_PROYECTO' => $uf->nombre_proyecto , 'FECHA' => $uf->clbod_semana.' - '.$uf->clbod_ano )); 
    } 
  }else { 
    $Coleccion_1[] = json_encode(array( 'SEMANA' => 0, 'ALMACEN' => 0, 'VISITA' => 0, 'PROYECTO' => 0, 'CODIGO' =>0, 'NOMBRE_PROYECTO' => 0 , 'FECHA' => 0));
  }

  if(!empty($segui_visit)){
    foreach ($segui_visit as $uf) { 
        $Visitador[] = json_encode(array('USUARIO'=> $uf->name,'FECHA'=>$uf->fecha,'NUMERO'=>$uf->numero));
    }
  }else{
        $Visitador[] = json_encode(array('USUARIO'=>0 ,'FECHA'=>0 ,'NUMERO'=>0 ));
  }

  if(!empty($segui_visit_acu)){
    foreach ($segui_visit_acu as $uf) { 
        $Acumulado[] = json_encode(array('USUARIO'=> $uf->name,'NUMERO'=>$uf->numero));
    }
  }else{
        $Acumulado[] = json_encode(array('USUARIO'=>0 ,'NUMERO'=>0 ));
  }
?>
<br><br>
</section>
<section class="content" style="">
    <div class="col-xs-12">
        <div class="row" style="display: block">
            <section id="contact" class="section-bg wow">
                <div class="container">
                    <header class="section-header">
                        <h3>Reportes - CheckList</h3>
                    </header>
                    <br>    
                    <form id="change_semana" method="POST" >
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="form-grou row">
                            
                            <div id="reports_seguimiento" class="col-md-12 col-sm-12 col-xs-12 col-lg-12" >
                            <br>
                                <div class="row">
                                    <div class="col-md-12 text-left bg-dark text-white p-2 mb-3" style="font-size: 15px;">
                                      <b>Reporte por Semana</b>
                                    </div>
                                    <div class="col col-md-4 col-lg-4 col-sm-12 col-xs-12 input-group input-group-sm">
                                    <select id="combobox_semana" class="form-control" name="ano_semana" data-live-search="true">
                                        @if(!empty($historicoCheckList))
                                            <option class=".d-print-none" value="{{ $año.'_'.$week }}">{{ $año.' Semana '.$week }}</option>
                                            @foreach($historicoCheckList as $historico)
                                                @if($historico->clbod_semana <> $week)
                                                <option value="{{ $historico->clbod_ano.'_'.$historico->clbod_semana }}">{{ $historico->clbod_ano.' Semana '.$historico->clbod_semana }}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option value="{{ $año.'_'.$week }}">{{ $año.' Semana '.$week }}</option>
                                        @endif                                 
                                    </select>
                                </div> 
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">  
                                        <br>
                                        <div class="box">
                                            <div class="containerReport" id="chart">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="reports_avance" class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                            <br class="hidden-md hidden-lg">
                                <div class="row">
                                <div class="col-md-12 text-left bg-dark text-white p-2 mb-3" style="font-size: 15px;">
                                      <b>Seguimiento por Proyecto</b>
                                </div>
                                    <div class="col col-md-4 col-lg-4 col-sm-12 col-xs-12 input-group input-group-sm">
                                        @if(!empty($historicoProy))
                                            <select id="combobox_proyecto" class="form-control" name="combobox_proyecto" data-live-search="true">
                                                @foreach($historicoProy as $historico)
                                                <option  value="{{ $historico->clbod_obra_id }}">{{ $historico->nom_cod_proyecto }}</option>
                                                @endforeach   
                                            </select>
                                        @endif  
                                    </div> 
                                </div>
                                
                                <div class="row .d-print">
                                    <div class="col-md-4 col-sm-12 col-xs-12 col-lg-4">  
                                        <br>
                                        <div class="box">
                                            <div class="containerReport" id="chart_3">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-sm-12 col-xs-12 col-lg-8">  
                                        <br>
                                        <div class="box">
                                            <div class="containerReport" id="chart_avance">

                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div id="reports_segui_visit" class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                            <br class="hidden-md hidden-lg">
                                <div class="row">
                                    <div class="col-md-12 text-left bg-dark text-white p-2 mb-3" style="font-size: 15px;">
                                          <b>Seguimiento por Visitador</b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 col-lg-4">  
                                        <br>
                                        <div class="box">
                                            <div class="containerReport" id="chart_visitador_acu">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-sm-12 col-xs-12 col-lg-8">  
                                        <br>
                                        <div class="box">
                                            <div class="containerReport" id="chart_visitador">

                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div id="reports_segui_vali" class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                            <br class="hidden-md hidden-lg">
                                <div class="row">
                                    <div class="col-md-12 text-left bg-dark text-white p-2 mb-3" style="font-size: 15px;">
                                          <b>Pendiente de Validación</b>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">  
                                        <br>
                                        <div class="box">
                                            <div class="table-responsive">
                                                @if(!empty($tabla_visita))
                                                <table class="table table-bordered table-striped" width="100%">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col" class="text-center">COD-PROYECTO</th>
                                                            <th scope="col" class="text-center">PROYECTO</th>
                                                            <th scope="col" class="text-center">SEMANA - AÑO</th>
                                                            <th scope="col" class="text-center">ALMACEN</th>  
                                                            <th scope="col" class="text-center">VISITA</th>     
                                                        </tr>
                                                    </thead>
                                                    <tbody id="body_validacion">
                                                        @foreach($tabla_visita as $table) 
                                                        <tr class="{{ $table->clbod_obra_id }}">
                                                            <td>{{ $table->clbod_obra_id }}</td>
                                                            <td>{{ $table->nombre_proyecto }}</td>
                                                            <td>{{ $table->clbod_semana.' - '.$table->clbod_ano }}</td>
                                                            <td <?php if($table->almacen == 'no_existe') echo 'class="text-danger"' ?>>
                                                                @if($table->almacen == 'no_existe')
                                                                    <b>Pendiente de CheckList</b>
                                                                @elseif($table->almacen == 'pendiente')
                                                                    <a class="btn btn-sm text-left text-white btn-warning mr-1" href="{{ URL::route('editOldAlmacen',['id' => $table->clbod_obra_id,'week'=> $table->clbod_semana,'año'=> $table->clbod_ano]) }}" title="FALTA DE VALIDAR"><i class="glyphicon glyphicon-remove-sign"></i></a>
                                                                @else
                                                                    <a class="btn btn-sm text-left text-white btn-primary mr-1" title="VALIDADO"><i class="glyphicon glyphicon-ok"></i></a>
                                                                @endif
                                                            </td>
                                                            <td <?php if($table->visita == 'no_existe') echo 'class="text-danger"' ?>>
                                                                @if($table->visita == 'no_existe')
                                                                    <b>Pendiente de CheckList</b>
                                                                @elseif($table->visita == 'pendiente')
                                                                    <a class="btn btn-sm text-left text-white btn-warning mr-1" href="{{ URL::route('editOldVisita',['id' => $table->clbod_obra_id,'week'=> $table->clbod_semana,'año'=> $table->clbod_ano]) }}" title="FALTA DE VALIDAR"><i class="glyphicon glyphicon-remove-sign"></i></a>
                                                                @else
                                                                    <a class="btn btn-sm text-left text-white btn-primary mr-1" title="VALIDADO"><i class="glyphicon glyphicon-ok"></i></a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                        </div>
                    </form>
                    <br>
                    
                </div>      
            </section>   
        </div>
</section>
@endsection

@section('script')
<script type="text/javascript" src="{{asset('apexcharts/dist/apexcharts.min.js')}}"></script>
<script>
$('select').selectpicker();

$(document).ready(function () {
    $(".preloader-wrapper").removeClass('active');
    id_combo = $("#combobox_semana").val();
    var colors = ['#5b9bd5','#808080','#ffd966','#333f4f','#548235','#c65911'];
    $("#combobox_semana" ).change(function() {
        id_combo = $("#combobox_semana").val();
        function esSuficientementeGrande(elemento) {
            return elemento.SEMANA == id_combo;
        }

        const result = Coleccion[tipo_dato].filter(esSuficientementeGrande);
        
        if(result != null){
            var almac =[];
            $.each(result,function(x, item){
                almac.push(parseFloat(item.ALMACEN));
            });

            var visit =[];
            $.each(result, function( x, item ) {
              visit.push(parseFloat(item.VISITA));
            });

            var seman =[];
            $.each(result, function( x, item ) {
              seman.push(item.SEMANA);
            });

            var proy =[];
            $.each(result, function( x, item ) {
              proy.push(item.PROYECTO);
            });
            
            chart.updateSeries([{
                    name: 'ALMACEN',
                    data: almac
                }, {
                    name: 'VISITA',
                    data: visit
                }],
                true
            );
           
            chart.updateOptions({ 
                xaxis: {
                    categories: proy,
                },
                tickAmount: 0,
            },true);
        }
    });


    var Coleccion = [];
    Coleccion.push([<?php echo implode(',',$Coleccion_1); ?>]);

    var tipo_dato = 0;

    function esSuficientementeGrande(elemento) {
        return elemento.SEMANA == id_combo;
    }

    const result = Coleccion[tipo_dato].filter(esSuficientementeGrande);
        
    if(result != null){    
        var almac =[];
        $.each(result,function(x, item){
            almac.push(parseFloat(item.ALMACEN));
        });

        var visit =[];
        $.each(result, function( x, item ) {
          visit.push(parseFloat(item.VISITA));
        });

        var seman =[];
        $.each(result, function( x, item ) {
          seman.push(item.SEMANA);
        });

        var proy =[];
        $.each(result, function( x, item ) {
          proy.push(item.PROYECTO);
        });
    
        var options = {
                chart: {
                    height: 350,
                    type: 'bar',
                },
                annotations: {
                  yaxis: [{
                    y: 71,
                    borderColor: '#dd4b39',
                    label: {
                      show: true,
                      text: '71',
                      style: {
                        color: "#fff",
                        background: '#dd4b39'
                      }
                    }
                  },
                  {
                    y: 93,
                    borderColor: '#f39c12',
                    label: {
                      show: true,
                      text: '93',
                      style: {
                        color: "#fff",
                        background: '#f39c12'
                      }
                    }
                  }]
                },
                colors:colors,
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return val + "%"
                    },
                },
                series: [{
                    name: 'ALMACEN',
                    data: almac
                }, {
                    name: 'VISITA',
                    data: visit
                }],
                legend: {
                    show: true,
                    position: 'top',
                },
                xaxis: {
                    categories: proy,
                    tickAmount: 0,

                },
                yaxis: {
                    title: {
                        text: '% Cumplimiento'
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return  val + "% Cumplimiento"
                        }
                    },
                }
            }

            var chart = new ApexCharts(
                document.querySelector("#chart"),
                options
            );

            chart.render();
        }

        $("#combobox_proyecto" ).change(function() {
            id_proyecto = $("#combobox_proyecto").val();
            function esSuficientemente(elemento) {
                return elemento.CODIGO == id_proyecto;
            }

            const result2 = Coleccion[tipo_dato].filter(esSuficientemente);

            if(result2 != null){
                var almac2 =[];
                $.each(result2,function(x, item){
                    if(item.ALMACEN !=null){
                        almac2.push(parseFloat(item.ALMACEN));
                    }
                });

                var visit2 =[];
                $.each(result2, function( x, item ) {
                    if(item.VISITA !=null){
                        visit2.push(parseFloat(item.VISITA));
                    }
                });

                var seman2 =[];
                $.each(result2, function( x, item ) {
                  seman2.push(item.FECHA);
                });

                var proy2 =[];
                $.each(result2, function( x, item ) {
                  proy2.push(item.PROYECTO);
                });

                var nombre2 =[];
                $.each(result2, function( x, item ) {
                  nombre2.push(item.NOMBRE_PROYECTO);
                });

                var almac_redo = [];
                var visit_redo = [];
                var fecha_redo = [];
                if(result2.length > 0){
                    fecha_redo.push(result2[result2.length-1].FECHA);
                    if(result2[result2.length-1].ALMACEN != null){
                        almac_redo.push(parseFloat(result2[result2.length-1].ALMACEN));
                    }else{
                        almac_redo.push(0);
                    }
                    if(result2[result2.length-1].VISITA != null){
                        visit_redo.push(parseFloat(result2[result2.length-1].VISITA));
                    }else{
                        visit_redo.push(0);
                    }


                    chart2.updateOptions({ 
                        title: {
                            text: proy2[0],
                            align: 'center'
                        },
                        xaxis: {
                            categories: seman2,
                            title: {
                                text: 'Semana'
                            }
                        },
                    },true);


                    chart2.updateSeries([{
                            data: almac2
                        }, {
                            data: visit2
                        }],true
                    );
                
                    chart3.updateSeries([almac_redo, visit_redo],true
                    );

                    chart3.updateOptions({
                        title: {
                            text: 'SEMANA: '+ fecha_redo,
                            align: 'center',
                            style: {
                              fontSize:  '16px',
                              color:  '#263238',
                            },
                        },
                    },true);
                }
            }
        });



        id_proyecto = $("#combobox_proyecto").val();

        function esSuficientemente(elemento) {
            return elemento.CODIGO == id_proyecto;
        }

        const result2 = Coleccion[tipo_dato].filter(esSuficientemente);
        if(result2 != null){
            var almac2 =[];
            $.each(result2,function(x, item){
                if(item.ALMACEN !=null){
                    almac2.push(parseFloat(item.ALMACEN));
                }
            });

            var visit2 =[];
            $.each(result2, function( x, item ) {
                if(item.VISITA !=null){
                    visit2.push(parseFloat(item.VISITA));
                }
            });

            var seman2 =[];
            $.each(result2, function( x, item ) {
              seman2.push(item.FECHA);
            });

            var proy2 =[];
            $.each(result2, function( x, item ) {
              proy2.push(item.PROYECTO);
            });

            var nombre2 =[];
            $.each(result2, function( x, item ) {
              nombre2.push(item.NOMBRE_PROYECTO);
            });

            var almac_redo = [];
            var visit_redo = [];
            var fecha_redo = [];
            if(result2.length > 0){
                fecha_redo.push(result2[result2.length-1].FECHA);
                if(result2[result2.length-1].ALMACEN != null){
                    almac_redo.push(parseFloat(result2[result2.length-1].ALMACEN));
                }else{
                    almac_redo.push(0);
                }
                if(result2[result2.length-1].VISITA != null){
                    visit_redo.push(parseFloat(result2[result2.length-1].VISITA));
                }else{
                    visit_redo.push(0);
                }
            }   


            var options2 = {
                chart: {
                    height: 350,
                    type: 'line',
                    shadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 1
                    },
                    toolbar: {
                        show: true
                    },
                    zoom: {
                      enabled: false
                    }
                },
                annotations: {
                  yaxis: [{
                    y: 71,
                    borderColor: '#dd4b39',
                    label: {
                      show: true,
                      text: '71',
                      style: {
                        color: "#fff",
                        background: '#dd4b39'
                      }
                    }
                  },
                  {
                    y: 93,
                    borderColor: '#f39c12',
                    label: {
                      show: true,
                      text: '93',
                      style: {
                        color: "#fff",
                        background: '#f39c12'
                      }
                    }
                  }]
                },
                legend: {
                    show: true,
                    position: 'top',
                },
                colors:colors,
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    curve: 'smooth'
                },
                series: [{
                        name: "ALMACEN",
                        data: almac2
                    },
                    {
                        name: "VISITA",
                        data: visit2
                    }
                ],
                title: {
                    text:  proy2[0],
                    align: 'center',

                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                markers: {
                    
                    size: 6
                },
                xaxis: {
                    categories: seman2,
                    title: {
                        text: 'SEMANA'
                    }
                },
                yaxis: {
                    title: {
                        text: '%'
                    },
                    min: 0,
                    max: 110               
                },
            }

            var chart2 = new ApexCharts(
                document.querySelector("#chart_avance"),
                options2
            );

            chart2.render();



            var options3 = {
                chart: {
                    height: 350,
                    type: 'radialBar',
                },
                title: {
                    text: 'SEMANA: '+ fecha_redo,
                    align: 'center',
                    style: {
                      fontSize:  '16px',
                      color:  '#263238',
                    },
                },
                colors:colors,
                plotOptions: {
                    radialBar: {
                        offsetY: -10,
                        startAngle: 0,
                        endAngle: 360,
                        hollow: {
                            margin: 5,
                            background: 'transparent',
                            image: undefined,
                        },
                        dataLabels: {
                            name: {
                                show: false,
                                
                            },
                            value: {
                                show: false,
                            }
                        }
                    }
                },
                series: [almac_redo, visit_redo],
                labels: ['ALMACEN', 'VISITA'],
                legend: {
                    show: true,
                    floating: true,
                    fontSize: '16px',
                    position: 'left',
                    offsetX: 80,
                    offsetY: 150,
                    labels: {
                        useSeriesColors: true,
                    },
                    markers: {
                        size: 0
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex] +"%"
                    },
                    itemMargin: {
                        horizontal: 1,
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            show: false
                        }
                    },
                }]
            }

           var chart3 = new ApexCharts(
                document.querySelector("#chart_3"),
                options3
            );
            
            chart3.render();
        }


        var ColeccionVisitador = [];
        ColeccionVisitador.push([<?php echo implode(',',$Visitador); ?>]);

        var fecha_visi =[];
        $.each(ColeccionVisitador[0], function( x, item ) {
            var filtrado2 = filteredWeek(fecha_visi,item.FECHA);
            if( filtrado2 == ''){
                fecha_visi.push(item.FECHA);
            }
        });

        var nro =[];
        $.each(ColeccionVisitador[0], function( x, item ) {
            
                nro.push(parseFloat(item.NUMERO));
            
        });

        var visitador =[];
        $.each(ColeccionVisitador[0],function(x, item){
            var filtrado = filterByProperty(visitador,'name',item.USUARIO);
            if( filtrado == ''){
                visitador.push({name:item.USUARIO,data:fecha_visi});
            }
        });
        $.each(visitador,function(x, item){
            var visi = [];
            var filtrado = filterByProperty(ColeccionVisitador[0],'USUARIO',item.name);
            $.each(filtrado,function(e, sub){
                var detalle = [];
                detalle.push(sub.FECHA);
                detalle.push(sub.NUMERO);
                visi.push(detalle);
            });
            var datos_nw = [];
            $.each(fecha_visi,function(e, sub){
                var index = visi.findIndex(x => x[0] === sub);
                if (index != -1) {
                    datos_nw.push(visi[index][1]);
                }else{
                    datos_nw.push(null);
                }
            });

            item.data = datos_nw;
        });

        
        function filterByProperty(array, prop, value){
           var filtered = [];
           for(var i = 0; i < array.length; i++){
             var obj = array[i];
             if (obj[prop]==value) {
                filtered.push(obj);
              }
           }
           return filtered;
         }

         function filteredWeek(array,value){
            var filteredx = [];
            for(var i=0;i<array.length;i++){
                var obj = array[i];
                if(obj == value){
                    filteredx.push(obj);
                }
            }
            return filteredx;
         }

        var optionsVisita = {
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                },
            },
            title: {
                text: 'N° Visitas Semanales',
                align: 'center',
            },   
            colors:colors,
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return val
                },
            },
            legend: {
                    show: true,
                    position: 'top',
                },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            series: visitador,
            xaxis: {
                categories: fecha_visi,
            },
            
            tooltip: {
                y: {
                    formatter: function (val) {
                        return  + val + " visita"
                    }
                }
            }
        }

        var chartVisita = new ApexCharts(
            document.querySelector("#chart_visitador"),
            optionsVisita
        );

        chartVisita.render();



        var ColeccionAcumulado= [];
        ColeccionAcumulado.push([<?php echo implode(',',$Acumulado); ?>]);

        var visit =[];
        var nombres = [];
        $.each(ColeccionAcumulado[0],function(x, item){
            nombres.push(item.USUARIO);
            visit.push({name:item.USUARIO,data:[item.NUMERO]});
        });

        var acumu =[];
        $.each(ColeccionAcumulado[0], function( x, item ) {
          acumu.push(parseFloat(item.NUMERO));
        });

        var optionsVisitaAcu = {
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    distributed: true
                }
            },
            title:{
                text: 'N° Total Visitas',
                align: 'center',
            },
            colors: colors,
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val;
                },
                style: {
                    fontSize: '12px',
                    colors: ["#ffffff"]
                }
            },
            series: [{
                name: 'Total',
                data: acumu }],
            xaxis: {
                categories: nombres,
                labels: {
                    style: {
                        colors: colors,
                        fontSize: '14px'
                    }
                }
           
            }
        }

        var chartVisitaAcu = new ApexCharts(
            document.querySelector("#chart_visitador_acu"),
            optionsVisitaAcu
        );

        chartVisitaAcu.render();
});


</script>
@endsection