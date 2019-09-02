$(document).ready(function() {
  $('select').selectpicker();
  $(".preloader-wrapper").removeClass('active');
});

$(document).on('init.dt', function ( e, settings ) {
    $('#dataTable').removeAttr('style');
    $('.btn_agregar')
      .attr('data-titulo', 'Agregar Topico')
      .attr('data-action', 'addPregunta')
      .attr('href', '#')
      .attr('data-enlace', '/addNewPregunta')
      .attr('data-tipo',0);
} );

$( document ).ready(function() {
    //llamar a los modales independientes
    jQuery(document).on("click", ".showAjaxModal", function(){
        var url = $(this).data('enlace');
        var titulo = $(this).data('titulo');
        var action = $(this).data('action');
        var idPadre = $(this).data('tipo');
        var tipo = $(this).data('select');
        $('#formulario').attr('action',action);
        $('#modal_titulo').html(titulo);
        $('#modal_ajax .modal-body').html('<div class="preloader text-center"><br><br><img class="center-block" src="https://www.gestionflesan.cl/controlflujo/images/grupo_flesan.png" style="width: 250px;"><br><br><p><img src="https://www.gestionflesan.cl/controlflujo/images/preloader_2019.gif" style="width: 25px;"><strong style="color: #adadad!important;font-size:13px;"> OBTENIENDO DATOS</strong></p></div>');
        $('#modal_ajax').modal('show', {backdrop: 'true'});
        $.ajax({
            url: url,
            success: function(response)
            {
                $('#modal_ajax .modal-body').html(response);
                $('#inputId').val(idPadre);
                if(tipo != ''){
                  $("#selectTipo").prop('disabled',true);
                  $("#selectTipo").val(tipo);
                }else{
                  $("#selectTipo").removeAttr('readonly');
                }
            }
        });
    });
});

