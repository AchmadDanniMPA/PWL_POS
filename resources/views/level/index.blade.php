@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-primary mt-1" onclick="modalAction('{{ url('/level/import/') }}')">Import Data</button>
                <button type="button" class="btn btn-sm btn-primary mt-1" onclick="modalAction('{{ url('/level/create_ajax/') }}')">Tambah</button>
                <button type="button" class="btn btn-sm btn-success mt-1" onclick="window.location.href='{{ url('/level/export_excel/') }}'">Export to Excel</button>
                <button type="button" class="btn btn-sm btn-danger mt-1" onclick="window.location.href='{{ url('/level/export_pdf/') }}'">Export to PDF</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table-bordered table-striped table-hover table-sm table" id="table_level">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Level</th>
                        <th>Nama Level</th>
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
        var dataLevel = $('#table_level').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('level/list') }}",
                "dataType": "json",
                "type": "POST",
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", width: "8%", orderable: false, searchable: false },
                { data: "level_kode", orderable: true, searchable: true },
                { data: "level_nama", orderable: true, searchable: true },
                { data: "action", orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush
