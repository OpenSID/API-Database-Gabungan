@extends('layouts.index')

@section('content_header')
    <h1>Edit Pengguna</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            @include('partials.flash_message')
            <div class="card card-outline card-primary">
                <div class="card-header">
                <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm"><i
                        class="fas fa-arrow-circle-left"></i></i>&ensp;Kembali ke Daftar Pengguna</a>
            </div>
            {{ html()->modelForm($user, 'PUT')->route('users.update', $user->id)->addClass('form-horizontal')->open() }}
            <div class="card-body">
                <div>
                    @include('users.fields')
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

            {{ html()->closeModelForm() }}

        </div>
    </div>
</div>
@endsection
