@extends('errors::illustrated-layout')

@section('code','404')
@section('title',_('Página no encontrada'))

@section('image')
<div style="background-image: url('img/404.jpg');" class="absolute pin  bg-no-repeat md:bg-left lg:bg-center bg-black">
</div>
@endsection

<!--@section('message', __('Lo sentimos, no se pudo encontrar la página que estás buscando.'))-->
