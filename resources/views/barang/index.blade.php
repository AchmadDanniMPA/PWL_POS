@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-info mt-1" onclick="modalAction('{{ url('/barang/import/') }}')">Import</button>
                <button type="button" class="btn btn-sm btn-primary mt-1" onclick="modalAction('{{ url('/barang/create_ajax/') }}')">Tambah</button>
                <button type="button" class="btn btn-sm btn-success mt-1" onclick="exportBarang()">Export Barang</button>
                <button type="button" class="btn btn-sm btn-danger mt-1" onclick="exportBarangPDF()">Export Barang PDF</button>
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
                        <label class="col-1 control-label col-form-label">Filter</label>
                        <div class="col-3">
                            <select class="form-control" id="kategori_id" name="kategori_id" required>
                                <option value="">- Semua -</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Filter berdasarkan Kategori</small>
                        </div>
                    </div>
                </div>
            </div>
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
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection
@push('js')
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        function exportBarangPDF() {
            window.location.href = "{{ route('barang.export_pdf') }}";
        }
        function exportBarang() {
            window.location.href = "{{ route('barang.export_excel') }}";
        }
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
            $(document).on('click', '.btn-edit', function() {
                var url = $(this).data('url');
                modalAction(url);
            });
            $(document).on('click', '.btn-delete', function() {
                var url = $(this).data('url');
                modalAction(url);
            });
        });
    </script>
@endpush
