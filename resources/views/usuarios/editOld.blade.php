<?php //print_r($usuario_app)?>
<input type="hidden" name="id_usuario" id="id_usuario" value="{{$usuario_app[0]->id_aplicacion_usuario}}">
<div class="form-grou row">
  <div class="align-self-center col col-md-12 col-lg-12 col-sm-12 col-xs-12">
    <label for="inputEmailEdit">E-Mail Corporativo</label>
    <input type="text" class="form-control col-md-12 col-lg-12 col-sm-12 col-xs-12 " readonly="true" id="inputEmailEdit" required="true" name="inputEmailEdit" placeholder="ejemplo@flesan.com.pe" value="{{$usuario_directorio[0]->userPrincipalName}}">
  </div>
</div>
<br>
<div class="form-grou row">
  <div class=" align-self-center col col-md-12 col-lg-12 col-sm-12 col-xs-12">
    <label for="idEmpresa" >Empresa</label>
    <select id="idEmpresa" readonly required="true" name="idEmpresa" class="form-control" autofocus="autofocus">
        @foreach($empresa as $emp)
        <option @if($usuario_app[0]->id_empresa == $emp->COD_EMPRESA)selected @else disabled @endif  style="font-size: 90%" value="{{ $emp->COD_EMPRESA }}">{{ $emp->NOMBRE_EMPRESA }}</option>
        @endforeach
    </select>
  </div>
</div>
<br>
<div class="form-grou row">
  <div class=" align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
    <label for="inputNombresEdit">Nombres</label>
    <input type="text" class="form-control" id="inputNombresEdit" readonly="true" required="true" name="inputNombresEdit" placeholder="Nombres Completos" value="{{$usuario_directorio[0]->givenName}}">
  </div>
  <div class=" align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
    <label for="inputApellidosEdit">Apellidos</label>
    <input type="text" class="form-control" id="inputApellidosEdit" readonly="true" required="true"  name="inputApellidosEdit" placeholder="Apellidos Completos" value="{{$usuario_directorio[0]->sn}}">
  </div>
</div>
<br>
<div class="form-grou row">
  <div class=" align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
    <label for="inputDniEdit" id="lblDniEdit" name="lblDni">DNI/CARNET EXTRANJERIA</label>
    <input type="number" class="form-control" id="inputDniEdit" min="0" readonly="true" name="inputDniEdit" placeholder="Documento Nac. Identidad" value="{{$usuario_directorio[0]->pager}}">
  </div>
  <div class=" align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
    <label for="selectPerfil">Perfil</label>
      <select id="selectPerfil" required="true" name="selectPerfil" class="form-control" style="background: white" autofocus="autofocus">
        <option value="" required="true" selected>Seleccione Perfil</option>
          @foreach($perfil as $perf)
          <option <?php if($usuario_app[0]->id_rol == $perf->id_rol) echo 'selected' ?>  style="font-size: 90%;" value="{{ $perf->id_rol }}">{{ $perf->nombre }}</option>
          @endforeach
      </select>
  </div>
</div>
<br>
<div class="form-grou row">
    <div class=" align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
        <label for="selectObra">Obra</label>
        <?php 
                    
              if($obj_permitido == '0'){
                $checkedTodas = 'checked';
                $hidden = 'hidden'; 
              }else{
                $checkedEsp = 'checked';
                $hidden = '';
              }

        ?>
        <div class="radio">
            <label ><input class="rd_obra" type="radio" name="optradio" value="0"  id="radioT"<?php if(isset($checkedTodas)){ echo $checkedTodas; } ?>>Todas</label>
            <label class="rd_visit <?php if($usuario_app[0]->id_rol == 12){echo 'hidden';}  ?>"><input class="rd_obra" id="radioE" type="radio" name="optradio" value="1" <?php if(isset($checkedEsp)){ echo $checkedEsp; } ?>>Especificar</label>
        </div>
        <div class="section-select <?php  if(isset($checkedTodas)){ echo $hidden; } ?>">
            <select id="selectObra"  name="selectObra[]" class="form-control "  multiple="multiple">
            @foreach($proyectos as $proy)
                @if(!empty($contenido_objeto))
                  <?php 
                      $selected = '';
                      if (array_search($proy->cod_proyecto, array_column($contenido_objeto, 'cod_proyecto')) !== FALSE ) {
                          $selected = 'selected';
                      }
                  ?>
                  <option <?php echo $selected;?> style="font-size: 90%;text-transform: uppercase;" value="{{ $proy->cod_proyecto }}">{{ $proy->id_unidad_negocio }} - {{ strtoupper($proy->nombre_proyecto) }}</option>
                @else
                  <option style="font-size: 90%;text-transform: uppercase;" value="{{ $proy->cod_proyecto }}">{{ $proy->id_unidad_negocio }} - {{ strtoupper($proy->nombre_proyecto) }}</option>
                @endif  
            @endforeach
            </select>
             
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        //$('#selectPerfil').selectpicker();
        $('#selectObra').select2({ width: '100%' });   
        
    });
</script>
