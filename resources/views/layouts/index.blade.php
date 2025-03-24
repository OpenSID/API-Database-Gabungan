@extends('adminlte::page')

@section('footer')
    <strong>Hak cipta © <?= date('Y') ?> <a href="https://opendesa.id">OpenDesa</a>.</strong>
    Seluruh hak cipta dilindungi.
    <div class="float-right d-none d-sm-inline-block">
        <b>Versi</b> {{ app_version() }}
    </div>
@endsection

@push('js')
    <script type="application/javascript">
        $('li#catatan-rilis').click(function(){
            Swal.fire({
                title: 'Menyimpan',
                didOpen: () => {
                    Swal.showLoading()
                },
            })
            $.get('/catatan-rilis', {}, function (data) {
                Swal.fire({
                    title: 'Catatan Rilis',
                    width: '90%',
                    html: data,
                    position: 'top',
                    confirmButtonText: 'Tutup',
                    showConfirmButton: false,
                    showCloseButton: true,
                    focusConfirm: false,
                    customClass: {
                        htmlContainer: 'text-left'
                    }

                })
            })
        })
    </script>
@endpush
