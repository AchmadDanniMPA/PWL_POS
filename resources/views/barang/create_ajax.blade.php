<form action="{{ url('/barang/ajax') }}" method="POST" id="form-tambah-barang">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kategori Barang</label>
                    <select name="kategori_id" id="kategori_id" class="form-control" required>
                        <option value="">- Pilih Kategori -</option>
                        @foreach($kategori as $item)
                            <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" name="barang_kode" id="barang_kode" class="form-control" required>
                    <small id="error-barang_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" name="barang_nama" id="barang_nama" class="form-control" required>
                    <small id="error-barang_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga Beli</label>
                    <input type="number" name="harga_beli" id="harga_beli" class="form-control" required>
                    <small id="error-harga_beli" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga Jual</label>
                    <input type="number" name="harga_jual" id="harga_jual" class="form-control" required>
                    <small id="error-harga_jual" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-tambah-barang").on('submit', function(event) {
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
                        $('.error-text').text('');
                        $.each(response.errors, function(key, value) {
                            $('#error-' + key).text(value[0]);
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal menyimpan data.'
                    });
                }
            });
        });
    });
</script>
