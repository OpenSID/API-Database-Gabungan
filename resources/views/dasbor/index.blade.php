@extends('layouts.index')

@section('title', 'Dasbor')

@section('content_header')
    <h1>Dasbor</h1>
@stop

@section('content')
    <x-adminlte-callout theme="warning">
        Selamat datang <b>{{ Auth::user()->name ?? '' }}</b> di Dasbor Utama
    </x-adminlte-callout>

    <div class="row">
        <div class="col-lg-12">

        </div>
    </div>
@endsection
