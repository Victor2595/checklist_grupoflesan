<div class="form-grou row">
    <div class="align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
        <label for="selectTipo">Tipo Checklist</label>
        <select id="selectTipo" required readonly name="selectTipo" class="form-control" autofocus="autofocus">
            <option value="" selected>Seleccione Perfil</option>
            <option value="1">Checklist Almacén</option>
            <option value="2">Checklist Visita</option>
        </select>
    </div>
    <div class="align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
        <label for="inputId" class="hidden">ID</label>
        <input type="text" class="form-control hidden" id="inputId"  required="true"  name="inputId" placeholder="ID">
    </div>
</div>
<br>
<div class="form-grou row">
    <div class="align-self-center col col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <label for="inputNombre">DESCRIPCION</label>
        <textarea type="text" class="form-control" id="inputNombre" required="true"  name="inputNombre" placeholder="Tópico..."></textarea>
    </div>
</div>