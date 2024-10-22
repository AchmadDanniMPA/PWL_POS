@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Transaksi Penjualan</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-info" onclick="modalAction('{{ url('/penjualan/import_view/') }}')">Import</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="modalAction('{{ url('/penjualan/create_ajax/') }}')">Tambah Penjualan</button>
                <a href="{{ url('/penjualan/export_excel') }}" class="btn btn-sm btn-success">Export Excel</a>
                <a href="{{ url('/penjualan/export_pdf') }}" class="btn btn-sm btn-danger">Export PDF</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table-bordered table-striped table-hover table-sm table" id="table_penjualan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Pembeli</th>
                        <th>Pembeli Level</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"></div>
@endsection
@push('js')
<script>
    function modalAction(url) {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function() {
        var dataPenjualan = $('#table_penjualan').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('penjualan/list') }}",
                "type": "POST"
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "penjualan_kode", orderable: true, searchable: true },
                { data: "pembeli", orderable: true, searchable: true },
                { data: "user_level", orderable: true, searchable: true },
                { data: "penjualan_tanggal", orderable: true, searchable: true },
                { data: "action", orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush
