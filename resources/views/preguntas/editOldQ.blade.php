<div class="form-grou row">
    <div class="align-self-center col col-md-6 col-lg-6 col-sm-12 col-xs-12">
        <label for="inputId" class="hidden">ID</label>
        <input type="text" class="form-control hidden" id="inputId"  required="true"  name="inputId" placeholder="ID">
        <input type="text" class="hidden" value="{{ $preguntas->clbod_preguntas_item_id }}" id="id" required="true" name="id">
    </div>
</div>
<br>
<div class="form-grou row">
    <div class="align-self-center col col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <label for="inputNombre">DESCRIPCION</label>
        <textarea type="text" class="form-control" id="inputNombre" required="true"  name="inputNombre" placeholder="Tópico...">{{ $preguntas->clbod_preguntas_nombre }}</textarea>
    </div>
</div>
