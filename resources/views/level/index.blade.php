@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('level/create') }}">Tambah</a>
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
@endsection

@push('js')
<script>
    $(document).ready(function() {
        var dataLevel = $('#table_level').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('level/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.level_kode = $('#level_kode').val();
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", width: "8%", orderable: false, searchable: false },
                { data: "level_kode", orderable: true, searchable: true },
                { data: "level_nama", orderable: true, searchable: true },
                { data: "action", orderable: false, searchable: false }
            ]
        });

        $('#level_kode').on('change', function() {
            dataLevel.ajax.reload();
        });
    });
</script>
@endpush
