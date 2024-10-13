@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
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
                        <th>Kode barang</th>
                        <th>Nama barang</th>
                        <th>Harga beli</th>
                        <th>harga jual</th>
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
            var databarang = $('#table_barang').DataTable({
                serverSide: true, // Menggunakan server-side processing
                ajax: {
                    "url": "{{ url('barang/list') }}", // Endpoint untuk mengambil data barang
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.kategori_id = $('#kategori_id').val(); // Mengirim data filter kategori
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex", // Menampilkan nomor urut dari Laravel DataTables addIndexColumn()
                        className: "text-center",
                        width: "8%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "kategori.kategori_nama",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "barang_kode",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "barang_nama",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "harga_beli",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "harga_jual",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "action", // Kolom aksi (Edit, Hapus)
                        orderable: false,
                        searchable: false
                    }
                ],
                error: function(xhr, error, thrown) {
                    alert("An error occurred: " + error);
                }
            });

            // Reload tabel saat filter kategori diubah
            $('#kategori_id').on('change', function() {
                databarang.ajax.reload(); // Memuat ulang tabel berdasarkan filter yang dipilih
            });
        });
    </script>
@endpush
