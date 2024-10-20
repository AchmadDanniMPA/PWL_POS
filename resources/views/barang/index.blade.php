@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-primary mt-1" onclick="modalAction('{{ url('/barang/create_ajax/') }}')">Tambah</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table-bordered table-striped table-hover table-sm table" id="table_barang">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('js')
<script>
    function modalAction(url) {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
    $(document).ready(function() {
        var databarang = $('#table_barang').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('barang/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.kategori_id = $('#kategori_id').val();
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", width: "8%", orderable: false, searchable: false },
                { data: "kategori.kategori_nama", orderable: true, searchable: true },
                { data: "barang_kode", orderable: true, searchable: true },
                { data: "barang_nama", orderable: true, searchable: true },
                { data: "harga_beli", orderable: true, searchable: true },
                { data: "harga_jual", orderable: true, searchable: true },
                { data: "action", orderable: false, searchable: false }
            ]
        });
        $('#kategori_id').on('change', function() {
            databarang.ajax.reload();
        });
    });
</script>
@endpush
