@extends('errors::illustrated-layout')

@section('code','401')
@section('title',_('Página Retringida'))

@section('image')
<div style="background-image: url('img/401.png');" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
</div>
@endsection

@section('message', __('Lo sentimos, tu no tienes acceso autorizado a esta pagina.'))