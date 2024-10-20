@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="modalAction('{{ url('/supplier/create_ajax/') }}')">Tambah</button>
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
