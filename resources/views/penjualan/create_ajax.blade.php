<form action="{{ url('/penjualan/store_ajax') }}" method="POST" id="form-create">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Pembeli</label>
                    <select name="user_id" class="form-control">
                        <option value="">Pilih Pembeli</option>
                        @foreach($users as $user)
                            <option value="{{ $user->user_id }}">{{ $user->nama }} - {{ $user->level->level_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-user_id" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kode Penjualan</label>
                    <input type="text" name="penjualan_kode" class="form-control">
                    <small id="error-penjualan_kode" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input type="datetime-local" name="penjualan_tanggal" class="form-control">
                    <small id="error-penjualan_tanggal" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Detail Barang</label>
                    <table class="table table-bordered" id="detail-barang-table">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="detail-barang-body">
                            <tr>
                                <td>
                                    <select name="barang_id[]" class="form-control">
                                        <option value="">Pilih Barang</option>
                                        @foreach($barang as $item)
                                            <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="harga[]" class="form-control">
                                </td>
                                <td>
                                    <input type="number" name="jumlah[]" class="form-control">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger remove-barang">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-success" id="add-barang">Tambah Barang</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#add-barang').click(function() {
            var newRow = `
                <tr>
                    <td>
                        <select name="barang_id[]" class="form-control">
                            <option value="">Pilih Barang</option>
                            @foreach($barang as $item)
                                <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="harga[]" class="form-control">
                    </td>
                    <td>
                        <input type="number" name="jumlah[]" class="form-control">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-barang">Hapus</button>
                    </td>
                </tr>
            `;
            $('#detail-barang-body').append(newRow);
        });
        $(document).on('click', '.remove-barang', function() {
            $(this).closest('tr').remove();
        });
        $("#form-create").validate({
            rules: {
                user_id: { required: true },
                penjualan_kode: { required: true, maxlength: 20 },
                penjualan_tanggal: { required: true },
                'barang_id[]': { required: true },
                'harga[]': { required: true, number: true },
                'jumlah[]': { required: true, digits: true },
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            tablePenjualan.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                            $('.error-text').text('');
                            $.each(response.errors, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal menyimpan data. Silakan coba lagi.'
                        });
                    }
                });
                return false;
            }
        });
    });
</script>
