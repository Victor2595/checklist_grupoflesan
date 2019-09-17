@extends('errors::illustrated-layout')

@section('code','500')
@section('title',_('Server Error'))

@section('image')
<div style="background-image: url('img/500.jpg');" class="absolute pin bg-no-repeat md:bg-left lg:bg-center bg-gray">
</div>
@endsection
