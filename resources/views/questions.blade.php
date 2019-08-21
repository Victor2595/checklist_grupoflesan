@extends('layouts.template')

@section('content')
    <br><br>
</section>
<section class="content" style="">
    <div class="col-xs-12">
        <div class="row" style="display: block">
            <section id="contact" class="section-bg wow">
                <div class="container">
                    <header class="section-header">
                        <h3>Gestión de Preguntas y Tópicos</h3>
                    </header>
                    <br>    
                    <a class="btn btn_agregar bg-danger text-white showAjaxModal btn-sm" data-titulo="Agregar Topico" data-action="addPregunta" data-enlace="/addNewPregunta" data-tipo="0" data-select="0"><i class="fa fa-user-plus"></i> Agregar Topico</a>
                    <br>
                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-dark">
                          <tr>
                            <th scope="col" class="text-center">Descripcion</th>
                            <th scope="col" class="text-center">Tipo</th>
                            <th scope="col" class="text-center">Estado</th>  
                            <th scope="col" class="text-center">Acción</th>     
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($arreglo as $tbl_p)
                            <tr class="{{ $tbl_p->id_cabecera }}">
                              <tr><td colspan="4" style="background-color: #dd4b39 !important; padding: 1px 0;"></td></tr>
                              <th colspan="3">{{ $tbl_p->cabecera }}</th>
                              <th>
                                <a href="#" class="btn btn-primary btn-sm showAjaxModal" data-titulo="Agregar Topico" data-action="addPregunta" data-enlace="/addNewPregunta" data-tipo="{{ $tbl_p->id_cabecera }}" data-select="{{ $tbl_p->tipo }}" value="Agregar" title="Agregar" style="font-size: 90%;"><i class="fa fa-plus"></i></a>
                                <a href="#" class="btn btn-info btn-sm showAjaxModal" data-titulo="Editar Topico" data-tipo="0" title="Editar" data-action="editPregunta" data-enlace="/editOldPregunta/{{ $tbl_p->id_cabecera  }}" data-select="{{ $tbl_p->tipo }}" style="font-size: 90%;"><i class="fas fa-pen"></i></a>
                                @if(count($tbl_p->hijo) == 0)
                                  @if($tbl_p->estado == 0)
                                  <a href="{{ route('states_preguntas',$tbl_p->id_cabecera) }}" class="btn btn-success btn-sm" value="Activar" style="font-size: 80%;" title="Activar"><i class="fa fa-thumbs-up"></i></a>    
                                  @else
                                  <a href="{{ route('states_preguntas',$tbl_p->id_cabecera) }}" class="btn btn-danger btn-sm" value="Inactivar" style="font-size: 80%;" title="Inactivar"><i class="fa fa-thumbs-down"></i></a></th>
                                  @endif
                                @endif
                            </tr> 
                            <?php $count=1 ?>                                 
                            @foreach($tbl_p->hijo as $tbl_h)
                              <tr class="{{ $tbl_h->clbod_preguntas_item_id }}">
                                <td style="font-size:80%;" class="text-left">{{ $count.'. '.$tbl_h->clbod_preguntas_nombre }}</td>
                                <td style="font-size:80%;text-transform: uppercase;"><?php echo ($tbl_h->clbod_preguntas_tipo == 1)?'Checklist Bodega':'Checklist Visita' ?></td>
                                <td style="font-size:80%;"><?php echo ($tbl_h->clbod_preguntas_estado == 1)?'ACTIVO':'INACTIVO' ?></td>
                                <td>
                                  <a href="#" class="btn btn-info btn-sm showAjaxModal" data-titulo="Editar Pregunta" data-tipo="1" title="Editar" data-action="editPregunta" data-enlace="/editOldPregunta/{{ $tbl_h->clbod_preguntas_item_id  }}" data-select="{{ $tbl_h->clbod_preguntas_tipo }}" style="font-size: 90%;"><i class="fas fa-pen"></i></a>
                                  @if($tbl_h->clbod_preguntas_estado == 0)
                                  <a href="{{ route('states_preguntas',$tbl_h->clbod_preguntas_item_id) }}" class="btn btn-success btn-sm" value="Activar" style="font-size: 80%;" title="Activar"><i class="fa fa-thumbs-up"></i></a>
                                  @else
                                  <a href="{{ route('states_preguntas',$tbl_h->clbod_preguntas_item_id) }}" class="btn btn-danger btn-sm" value="Inactivar" style="font-size: 80%;" title="Inactivar"><i class="fa fa-thumbs-down"></i></a>
                                  @endif
                                </td>
                              </tr>
                              <?php $count++; ?>
                            @endforeach
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
            <form id="formulario" method="POST">
              @csrf
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
<script type="text/javascript" src="{{asset('js/question.js')}}"></script>
@endsection