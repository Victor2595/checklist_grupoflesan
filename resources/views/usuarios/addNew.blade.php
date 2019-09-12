<div class="form-grou row">
    <div class="align-self-center col col-md-9 col-lg-9 col-sm-12 col-xs-12">
        <label for="inputEmail">E-Mail Corporativo</label>
        <input type="text" class="form-control col-sm-12 col-xs-12 " id="inputEmail" required="true" name="inputEmail" placeholder="ejemplo@flesan.com.pe">
    </div>
    <div class="text-left col col-md-3 col-lg-3 col-sm-12 col-xs-12">
        <a class="btn btn-danger col-md-4 col-lg-4 col-sm-12 col-xs-12" id="btnBuscar" style="margin-top: 32px;" href="#" role="button" onclick="buscarUser()"><i class="fa fa-search"></i> </a>
    </div>
</div>
<div class="form-grou row">
    <div class="align-self-center col col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <label id="mensaje" style="color: #d31a2b;display: none" >No hay usuarios encontrados</label>
    </div>
</div>
<br>
<div class="form-grou row">
    <div class=" align-self-center col col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <label for="idEmpresa" >Empresa</label>
        <select id="idEmpresa" readonly required="true" name="idEmpresa" class="form-control" autofocus="autofocus">
        @foreach($empresa as $emp)
        <option disabled style="font-size: 90%" value="{{ $emp->COD_EMPRESA }}">{{ $emp->NOMBRE_EMPRESA }}</option>
        @endforeach
        </select>
    </div>
</div>
<br>
<div class="form-grou row">
    <div class=" align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
        <label for="inputNombres">Nombres</label>
        <input type="text" class="form-control" id="inputNombres" readonly="true" required="true" name="inputNombres" placeholder="Nombres Completos">
    </div>
    <div class=" align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
        <label for="inputApellidos">Apellidos</label>
        <input type="text" class="form-control" id="inputApellidos" readonly="true" required="true"  name="inputApellidos" placeholder="Apellidos Completos">
    </div>
</div>
<br>
<div class="form-grou row">
    <div class=" align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
        <label for="inputDni" id="lblDni" name="lblDni">DNI/CARNET EXTRANJERIA</label>
        <input type="number" class="form-control" id="inputDni" min="0" readonly="true" name="inputDni" placeholder="Documento Nac. Identidad">
    </div>
    <div class=" align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
        <label for="selectPerfil">Perfil</label>
        <select id="selectPerfil" required="true" name="selectPerfil" class="form-control" autofocus="autofocus">
        <option value="" required="true" selected>Seleccione Perfil</option>
        @foreach($perfil as $perf)
        <option  style="font-size: 90%" value="{{ $perf->id_rol }}">{{ $perf->nombre }}</option>
        @endforeach
        </select>
    </div>
</div>
<br>
<div class="form-grou row">
    <div class=" align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
        <label for="selectObra">Obra</label>
        <div class="radio">
            <label><input class="rd_obra" type="radio" id="radioT" name="optradio" value="0" checked style="margin: auto">Todas</label>
            <label class="rd_visit"><input class="rd_obra" id="radioE" type="radio" name="optradio" value="1" style="margin: auto">Especificar</label>
        </div>
        <div class="section-select hidden">
            <select id="selectObra" name="selectObra[]" class="form-control"  multiple="multiple">
            @foreach($proyectos as $proy)
                @if(!empty($array_cod))
                    @if(array_search($proy->cod_proyecto, $array_cod)==false)
                    <option style="font-size: 90%;text-transform: uppercase;" value="{{ trim($proy->cod_proyecto) }}">{{ $proy->id_unidad_negocio }} - {{ strtoupper($proy->nombre_proyecto) }}</option>
                    @endif
                @else
                    <option style="font-size: 90%;text-transform: uppercase;" value="{{ trim($proy->cod_proyecto) }}">{{ $proy->id_unidad_negocio }} - {{ strtoupper($proy->nombre_proyecto) }}</option>
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