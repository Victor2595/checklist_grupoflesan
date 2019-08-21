@extends("layouts.index")
@section("content")
<br>
<br>
<style type="text/css">
  .hidden{
      visibility: hidden;
  }
</style>
<section id="contact" class="section-bg wow">
    <div class="container">
        <header class="section-header">
            <h3>Gestión de Usuario</h3>
        </header>
		  	<div class="box">
            <br>
			  		<div class="box-body">
			  			<table class="table table-bordered table-striped" style="visibility:hidden;" id="dataTable" width="100%" cellspacing="0">
			            <thead class="thead-dark hidden-xs hidden-sm hidden-md">
			              <tr>
			              	<th data-priority="1" style="font-size: 90%">Nombre</th>
			                <th data-priority="2" style="font-size: 90%">Perfil</th>
			                <th style="font-size: 90%">E-Mail</th>
			                <th style="font-size: 90%">Estado</th>
			                <th data-priority="3" style="font-size: 90%">Acción</th>
			              </tr>
			            </thead>
			            <tbody class="hidden-xs hidden-sm hidden-md">
			            	@foreach($app as $tabla_user)
			              	<tr class="{{ $tabla_user->id_aplicacion_usuario }}">
			              		<td style="font-size:80%;">{{ $tabla_user->name }}</td>
			              		<td style="font-size:80%;">{{ $tabla_user->nombre_rol }}</td>
			              		<td style="font-size:80%;">{{ $tabla_user->username }}</td>
			              		<td style="font-size:80%;">{{ $tabla_user->estado_sesion }}</td>
                        <td>
			                		@if($tabla_user->estado == 0 && $tabla_user->estado_validacion == 1)
			                		<a href="{{ route('states_usuarios',$tabla_user->id_aplicacion_usuario) }}" class="btn btn-success btn-sm" value="Activar" style="font-size: 90%;"><i class="fa fa-thumbs-up"></i></a>
			                		@elseif($tabla_user->estado == 1 && $tabla_user->estado_validacion == 1)
			                		<a href="{{ route('states_usuarios',$tabla_user->id_aplicacion_usuario) }}" class="btn btn-danger btn-sm" value="Inactivar" style="font-size: 80%;"><i class="fa fa-thumbs-down"></i></a>
			                		@endif
                          <a href="#" class="btn btn-info btn-sm showAjaxModal" data-titulo="Editar Usuario" data-action="editUsuario" data-enlace="/editOldUsuario/{{ ($tabla_user->id_aplicacion_usuario) }}" style="font-size: 90%;"><i class="fa fa-pencil"></i></a>
			                	</td>
			              	</tr>
			              	@endforeach
			            </tbody>
			          </table>
			  		</div>
        </div>
    </div>
</section>
<section>
  <!-- Modal Nuevo Curso-->
  <div class="modal fade" id="modal_ajax" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document" style="overflow-y: initial !important; margin: 0px; left: 50%; top: 50%; transform: translate(-50%, -50%);">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id="modal_titulo" class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formulario">
          <div class="modal-body" style="max-height: 71vh;overflow-y: auto;overflow-x: hidden;">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <input type="submit" class="btn btn-default bg-success text-white" value="Guardar" />
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
@section("scripts")
<script src="{{asset('js/excel.js')}}" type="text/JavaScript"></script>
<script src="{{ asset('sweetalert/dist/sweetalert.min.js') }}" type="text/javascript"></script>
<script>
    $(document).on( 'init.dt', function ( e, settings ) {
        $('#dataTable').removeAttr('style');
        $('.btn_agregar')
          .attr('data-titulo', 'Agregar Usuario')
          .attr('data-action', 'addUsuario')
          .attr('href', '#')
          .attr('data-enlace', '/addNewUsuario');
    } );
    $( document ).ready(function() {
        datatable_detalle = $('#dataTable').DataTable( {
          destroy: true,
            responsive: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, -1 ],
                [ '10', '25', '50', '100', 'Mostrar todo' ]
            ],
            buttons:
            [
              {
                  text: '<i class="fa fa-user-plus"></i> Nuevo usuario',
                  className: "bg-danger text-white showAjaxModal btn_agregar",
              },
              {
                extend: 'copy'
              },
              {
                extend: 'pdf'
              },
              {
                extend: 'excel'
              },
              {
                extend: "pageLength",
              }
            ]
        })
      	//llamar a los modales independientes
      	jQuery(document).on("click", ".showAjaxModal", function(){
      			var url = $(this).data('enlace');
      			var titulo = $(this).data('titulo');
      			var action = $(this).data('action');
      			$('#formulario').attr('action',action);
      			$('#modal_titulo').html(titulo);
      			$('#modal_ajax .modal-body').html('<div class="preloader text-center"><br><br><img class="center-block" src="https://www.gestionflesan.cl/controlflujo/images/grupo_flesan.png" style="width: 250px;"><br><br><p><img src="https://www.gestionflesan.cl/controlflujo/images/preloader_2019.gif" style="width: 25px;"><strong style="color: #adadad!important;font-size:13px;"> OBTENIENDO DATOS</strong></p></div>');
      			$('#modal_ajax').modal('show', {backdrop: 'true'});
      			$.ajax({
        				url: url,
        				success: function(response)
        				{
        				    $('#modal_ajax .modal-body').html(response);
        				}
      			});
      	});
    });
    function buscarUser(){
        $( "#idEmpresa option" ).prop('disabled',true);
        $( "#funidad_negocio option" ).prop('disabled',true);
        if($("#inputEmail").val() != "" ){
            $email = $("#inputEmail").val();
            $.get('/usuarios_informacion_carga/'+$email, function(request){
                if(request.length >= 1){
                    $("#idEmpresa").val(request[0].id_company);
                    if (request[0].id_company == '0018') {
                        $("#funidad_negocio").val(request[0].id_company+'-OP');
                    }else{
                        $("#funidad_negocio").val(request[0].id_company);
                    }                                     
                    $("#inputDni").val(request[0].pager);
                    $("#inputNombres").val(request[0].givenName);
                    $("#inputApellidos").val(request[0].sn);
                    $("#mensaje").prop("style","display : none");
                    $( "#idEmpresa option:selected" ).removeAttr('disabled');
                    $( "#funidad_negocio .emp_"+request[0].id_company ).removeAttr('disabled');
                }else{
                    $("#mensaje").prop("style","color : #d31a2b");
                    $("#inputDni").val("");
                    $("#inputNombres").val("");
                    $("#inputApellidos").val("");
                    $("#inputEmpresa").val("");
                    $("#idEmpresa").val("");
                }
            });
        }else {
            swal("¡Denegado!", "Ingrese una direccion electronica primero.", "error");
        }
    }
    function filtrar_objetos(tipo_objeto) {
        var unidad_negocio = $( "#funidad_negocio option:selected" ).val();
        $('#fobjeto_permitido').html('<option value="0">Cargando...</option>'); 
        $.ajax({
          url: '/getObjetopermitido',
          data: {"_token": "{{ csrf_token() }}", unidad_negocio:unidad_negocio, tipo_objeto:tipo_objeto},
          type: 'POST',
          datatype: 'JSON',
          success: function (response) {
            if (response.length > 0) {                
              $('#fobjeto_permitido').html('<option value="0">Elegir objeto</option>'); 
              $.each( response, function( i, item ) {
                $('#fobjeto_permitido').append('<option value="'+item.id_objeto+'">'+item.descripcion.toUpperCase() +'</option>'); 
              });
            }
          },
          error: function (response) {
          }
        });
    }
    $(document).on('change', '#selectPerfil', function() {
        var codigo_perfil = $( "#selectPerfil option:selected" ).val();
        if (codigo_perfil == 1 || codigo_perfil == 5) {
          $('#cont_objeto_permitido').removeClass('hidden');
          filtrar_objetos(codigo_perfil);
        }else{
          $('#fobjeto_permitido').html('<option value="0">Sin uso</option>'); 
          $('#cont_objeto_permitido').addClass('hidden');
        }
    });
    $(document).on('change', '#funidad_negocio', function() {
        var codigo_perfil = $( "#selectPerfil option:selected" ).val();
        if (codigo_perfil == 1 || codigo_perfil == 5) {
          $('#cont_objeto_permitido').removeClass('hidden');
          if (codigo_perfil == 1) {filtrar_objetos(codigo_perfil);}          
        }else{
          $('#fobjeto_permitido').html('<option value="0">Sin uso</option>'); 
          $('#cont_objeto_permitido').addClass('hidden');
        }
    });
</script>
@endsection
