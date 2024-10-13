@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('supplier/create') }}">Tambah</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Filter</label>
                    <div class="col-4">
                        <select class="form-control" id="supplier_kode" name="supplier_kode">
                            <option value="">- Semua -</option>
                            @foreach ($suppliers as $item) <!-- Use $suppliers here -->
                                <option value="{{ $item->supplier_kode }}">{{ $item->supplier_kode }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kode Supplier</small>
                    </div>
                </div>
            </div>
        </div>
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

@push('css')
@endpush

@push('js')
<script>
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
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    width: "8%",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "supplier_kode",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "supplier_nama",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "supplier_alamat",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "action",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#supplier_kode').on('change', function() {
            dataSupplier.ajax.reload();
        });
    });
</script>
@endpush
