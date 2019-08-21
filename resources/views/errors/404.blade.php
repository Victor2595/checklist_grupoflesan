@extends('errors::illustrated-layout')

@section('code','404')
@section('title',_('Página no encontrada'))

@section('image')
<div style="background-image: url('img/404.png');" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
</div>
@endsection

@section('message', __('Lo sentimos, no se pudo encontrar la página que estás buscando.'))
