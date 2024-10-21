@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-sm btn-info mt-1" onclick="modalAction('{{ url('/supplier/import/') }}')"><i class="fa fa-upload"></i> Import</button>
            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="modalAction('{{ url('/supplier/create_ajax/') }}')">Tambah</button>
            <a href="{{ url('/supplier/export_excel') }}" class="btn btn-sm btn-success mt-1"><i class="fa fa-file-excel"></i> Export Excel</a>
            <a href="{{ url('/supplier/export_pdf') }}" class="btn btn-sm btn-danger mt-1"><i class="fa fa-file-pdf"></i> Export PDF</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table-bordered table-striped table-hover table-sm table" id="table_supplier">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
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
            var dataSupplier = $('#table_supplier').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('supplier/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.supplier_kode = $('#supplier_kode').val();
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", width: "8%", orderable: false, searchable: false },
                    { data: "supplier_kode", orderable: true, searchable: true },
                    { data: "supplier_nama", orderable: true, searchable: true },
                    { data: "supplier_alamat", orderable: true, searchable: true },
                    { data: "action", orderable: false, searchable: false }
                ]
            });
            $('#supplier_kode').on('change', function() {
                dataSupplier.ajax.reload();
            });
        });
    </script>
@endpush
