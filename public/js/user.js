jQuery(document).on("change", ".rd_obra", function(){
  if ($(this).is(':checked')) {
      var valor_seleccionado = $(this).val();
      if (valor_seleccionado == 0) {
        $('.section-select').addClass('hidden');
        $('#selectObra').val(null).trigger('change');
        $('#selectObra').prop('required',false);;
      }else{
        $('.section-select').removeClass('hidden');
        $('#selectObra').prop('required',true);;
      }
  }
});

$(document).ready(function() {
  $('select').selectpicker();
  $(".preloader-wrapper").removeClass('active');
});

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
    if($("#inputEmail").val() != "" ){
        $email = $("#inputEmail").val();
        $.get('/usuarios_informacion_carga/'+$email, function(request){
            if(request.length >= 1){
                $("#idEmpresa").val(request[0].id_company);
                                                    
                $("#inputDni").val(request[0].pager);
                $("#inputNombres").val(request[0].givenName);
                $("#inputApellidos").val(request[0].sn);
                $("#mensaje").prop("style","display : none");
                $( "#idEmpresa option:selected" ).removeAttr('disabled');
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
        swal("¡Denegado!", "Ingrese una direccion electronica primero.", 'error');
    }
} 



jQuery(document).on("change", "#selectPerfil", function(){
  $id = $(this).val();
  if($id == 12){
    $(".rd_visit").addClass('hidden');
    $('.section-select').addClass('hidden');
    $('#selectObra').val(null).trigger('change');
    if($(".rd_obra").val() == 0){
      $("#radioT").prop('checked',true);
    }

  }else{
    $(".rd_visit").removeClass('hidden');
  }
});