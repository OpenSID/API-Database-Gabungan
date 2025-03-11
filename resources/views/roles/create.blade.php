@extends('layouts.index')

@section('content_header')
    <h1>Tambah Peran</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-lg-12">
            @include('partials.flash_message')
            <div class="card card-outline card-primary">
                <div class="card-header">
                <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm"><i
                        class="fas fa-arrow-circle-left"></i></i>&ensp;Kembali ke Daftar Peran</a>
            </div>
            {{ html()->form('POST', route('roles.store'))->addClass('form-horizontal')->open() }}

            <div class="card-body">

                <div>
                    @include('roles.fields')
                </div>

            </div>

            <div class="card-footer">
                <div class="form-group">
                    <div class="offset-2">
                        {{ html()->reset('Batal')->addClass('btn btn-sm btn-danger') }}
                        {{ html()->submit('Simpan')->addClass('btn btn-sm btn-primary') }}
                    </div>
                </div>
            </div>

            {{ html()->form()->close() }}

        </div>
    </div>
</div>
@endsection
