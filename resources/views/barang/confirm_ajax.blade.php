@empty($barang)
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
                Data barang tidak ditemukan.
            </div>
            <a href="{{ url('/barang') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/barang/' . $barang->barang_id . '/delete_ajax') }}" method="POST" id="form-delete-barang">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                    Apakah Anda yakin ingin menghapus data barang ini?
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Kode Barang :</th>
                        <td class="col-9">{{ $barang->barang_kode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Barang :</th>
                        <td class="col-9">{{ $barang->barang_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Harga Beli :</th>
                        <td class="col-9">{{ $barang->harga_beli }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Harga Jual :</th>
                        <td class="col-9">{{ $barang->harga_jual }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Ya, Hapus</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-delete-barang").on('submit', function(event) {
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        $('#table_barang').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal menghapus data.'
                    });
                }
            });
        });
    });
</script>
@endempty