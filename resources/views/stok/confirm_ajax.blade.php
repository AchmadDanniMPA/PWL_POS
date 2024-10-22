@empty($stok)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data stok yang Anda cari tidak ditemukan.
            </div>
            <a href="{{ url('/stok') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/stok/' . $stok->stok_id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data stok berikut?</p>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID Stok :</th>
                        <td class="col-9">{{ $stok->stok_id }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Supplier :</th>
                        <td class="col-9">{{ $stok->supplier->supplier_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Barang :</th>
                        <td class="col-9">{{ $stok->barang->barang_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jumlah :</th>
                        <td class="col-9">{{ $stok->stok_jumlah }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal :</th>
                        <td class="col-9">{{ $stok->stok_tanggal }}</td>
                    </tr>
                </table>
                <div class="alert alert-warning">
                    <i class="icon fas fa-exclamation-triangle"></i> Data yang telah dihapus tidak dapat dikembalikan!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-delete").validate({
            rules: {},
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            tableStok.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Tidak dapat menghapus data stok. Silakan coba lagi nanti.'
                        });
                    }
                });
                return false;
            }
        });
    });
</script>
@endempty
