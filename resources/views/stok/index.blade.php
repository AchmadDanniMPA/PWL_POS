@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Stok Barang</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-info" onclick="modalAction('{{ url('/stok/import_view/') }}')">Import</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="modalAction('{{ url('/stok/create_ajax/') }}')">Tambah Stok</button>
                <a href="{{ url('/stok/export_excel') }}" class="btn btn-sm btn-success">Export Excel</a>
                <a href="{{ url('/stok/export_pdf') }}" class="btn btn-sm btn-danger">Export PDF</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table-bordered table-striped table-hover table-sm table" id="table_stok">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier</th>
                        <th>Barang</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection
@push('js')
<script>
    function modalAction(url) {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
    $(document).ready(function() {
        var dataStok = $('#table_stok').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('stok/list') }}",
                "type": "POST",
                "data": function(d) {
                    d.supplier_id = $('#supplier_id').val();
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "supplier.supplier_nama", orderable: true, searchable: true },
                { data: "barang.barang_nama", orderable: true, searchable: true },
                { data: "stok_tanggal", orderable: true, searchable: true },
                { data: "stok_jumlah", orderable: true, searchable: true },
                { data: "action", orderable: false, searchable: false }
            ]
        });
        $('#supplier_id').on('change', function() {
            dataStok.ajax.reload();
        });
    });
</script>
@endpush

