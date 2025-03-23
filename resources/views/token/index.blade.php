@extends('layouts.index')

@section('title', 'Token API')

@section('content_header')
    <h1>Token API</h1>
@stop

@section('content')
    <x-adminlte-callout theme="warning">
        Token API digunakan untuk mengakses API yang disediakan oleh sistem ini adalah sebagai berikut:
        <br>
        <div class="col-8">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-key"></i></span>
                </div>
                <textarea id="token" type="text" class="form-control" readonly>{{ $token }}</textarea>
                <div class="input-group-append">
                    <div class="input-group-text"><a href="#" id="copy" title="Copy"><i
                                class="far fa-copy"></i></a></div>
                </div>
            </div>
        </div>
        <div class="col-8 mt-2">
            <a href="{{ route('token', ['generate' => true]) }}"><button class="btn btn-primary">Generate Token</button></a>
        </div>
    </x-adminlte-callout>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#copy').on('click', function() {
                $('#token').select();
                document.execCommand('copy');
            });
        });
    </script>
@endpush
