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
                        <h3>Gestión de Usuarios</h3>
                    </header>
                    <br>    
                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-dark">
                          <tr>
                            <th scope="col" class="text-center">Obra</th>
                            <th scope="col" class="text-center">Correo</th>
                            <th scope="col" class="text-center">Perfil</th>
                            <th scope="col" class="text-center">Estado</th>  
                            <th scope="col" class="text-center">Acción</th>     
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($table_usuario as $table) 
                                <?php $array_obj = explode(';', $table->objeto_permitido);
                                    $obj_permitido = '';
                                    foreach ($array_obj as $rl) {
                                        $obj_permitido .= '<p style="margin:0;">'.$rl.'</p>';                                                    
                                    }
                                ?>
                                <tr class="{{ $table->id_usuario_rol }}">
                                    <td style="font-size:80%;text-transform: uppercase;"><?php echo $obj_permitido ?></td>
                                    <td style="font-size:80%;">{{ $table->username }}</td>
                                    <td style="font-size:80%;text-transform: uppercase;">{{ $table->nombre }}</td>
                                    <td style="font-size:80%;">{{ $table->estado }}</td>
                            <td>
                                        @if($table->estado_sesion == 0 && $table->estado_validacion == 1)
                                        <a href="" class="btn btn-success btn-sm" value="Activar" title="Activar" style="font-size: 90%;"><i class="fa fa-thumbs-up"></i></a>
                                        @elseif($table->estado_sesion == 1 && $table->estado_validacion == 1)
                                        <a href="" class="btn btn-danger btn-sm" value="Inactivar" style="font-size: 80%;" title="Inactivar"><i class="fa fa-thumbs-down"></i></a>
                                        @endif
                              <a href="#" class="btn btn-info btn-sm showAjaxModal" data-titulo="Editar Usuario" title="Editar" data-action="editUsuario" data-enlace="/editOldUsuario/{{ ($table->id_aplicacion_usuario) }}" style="font-size: 90%;"><i class="fas fa-pen"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                      </table>
                </div>
            </section>      
        </div>
    </div>
</section>
<section>
    <div class="modal fade" id="modal_ajax" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document" style="overflow-y: initial !important; margin: 0px; left: 50%; top: 50%; transform: translate(-50%, -50%);">
          <div class="modal-content">
            <div class="modal-header bg-dark">
              <h4 id="modal_titulo" class="modal-title" style="border-bottom:0px;color:#ffffff"></h4>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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
<script type="text/javascript" src="{{asset('js/user.js')}}"></script>
@endsection