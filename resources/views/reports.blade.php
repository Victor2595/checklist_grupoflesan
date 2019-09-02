@extends('layouts.template')

@section('style')
        <style rel="stylesheet">
            @import url(https://fonts.googleapis.com/css?family=Roboto);

            body {
              font-family: Roboto, sans-serif;
            }

            #chart {
              max-width: 650px;
              margin: 35px auto;
            }
        </style>
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
                    </header>
                    <br>    
                    <form id="change_semana" class="hidden-xs " method="POST" style="width: 400px;">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="form-grou row">
                            
                            <div class="row">
  <div class="col-3">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
      <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</a>
      <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</a>
      <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</a>
      <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a>
    </div>
  </div>
  <div class="col-9">
    <div class="tab-content" id="v-pills-tabContent">
      <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab"><div id="chart">
                        
                    </div></div>
      <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">...</div>
      <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>
      <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>
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
    
    var options = {
      chart: {
        type: 'bar'
      },
      series: [{
        name: 'sales',
        data: [30,40,45,50,49,60,70,91,125]
      }],
      xaxis: {
        categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]
      }
    }

    var chart = new ApexCharts(document.querySelector("#chart"), options);

    chart.render();
});

</script>
@endsection