$(document).ready(function() {
  $('select').selectpicker();
  $(".preloader-wrapper").removeClass('active');
});



$( document ).ready(function() {
    //llamar a los modales independientes
    jQuery(document).on("click", ".showAjaxModal", function(){
        var url = $(this).data('enlace');
        var titulo = $(this).data('titulo');
        var action = $(this).data('action');
        var idPadre = $(this).data('tipo');
        var tipo = $(this).data('select');
        var pregunta = $(this).data('tabla');
        

        $('#formulario').attr('action',action);
        $('#modal_titulo').html(titulo);
        $('#modal_ajax .modal-body').html('<div class="preloader text-center"><br><br><img class="center-block" src="https://www.gestionflesan.cl/controlflujo/images/grupo_flesan.png" style="width: 250px;"><br><br><p><img src="https://www.gestionflesan.cl/controlflujo/images/preloader_2019.gif" style="width: 25px;"><strong style="color: #adadad!important;font-size:13px;"> OBTENIENDO DATOS</strong></p></div>');
        $('#modal_ajax').modal('show', {backdrop: 'true'});
        $.ajax({
            url: url,
            success: function(response)
            {
                $('#modal_ajax .modal-body').html(response);
                $("#selectTipo").val(pregunta);
                if(pregunta != undefined){
                  $("#selectTipo").addClass('hidden');
                  $("#labelTipo").addClass('hidden');
                }
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

