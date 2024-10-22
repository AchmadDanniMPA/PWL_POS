@empty($penjualan)
    <div class="modal-dialog modal-lg" role="document">
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
                    Data penjualan yang Anda cari tidak ditemukan.
                </div>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus transaksi penjualan ini?</p>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">ID Penjualan :</th>
                            <td class="col-9">{{ $penjualan->penjualan_id }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Kode :</th>
                            <td class="col-9">{{ $penjualan->penjualan_kode }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Pembeli :</th>
                            <td class="col-9">{{ $penjualan->pembeli }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal :</th>
                            <td class="col-9">{{ $penjualan->penjualan_tanggal }}</td>
                        </tr>
                    </table>
                    <div class="alert alert-warning">
                        <i class="icon fas fa-exclamation-triangle"></i> Data yang telah dihapus tidak dapat dikembalikan!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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
                                dataPenjualan.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Gagal menghapus data. Silakan coba lagi.'
                            });
                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endempty
