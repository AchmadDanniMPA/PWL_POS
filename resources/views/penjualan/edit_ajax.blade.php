<form action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <input type="hidden" name="pembeli" id="pembeli" value="{{ $penjualan->pembeli }}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Penjualan</h5>
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
                            <option value="{{ $user->user_id }}" {{ $penjualan->user_id == $user->user_id ? 'selected' : '' }}>
                                {{ $user->nama }} - {{ $user->level->level_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-user_id" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kode Penjualan</label>
                    <input type="text" name="penjualan_kode" class="form-control" value="{{ $penjualan->penjualan_kode }}">
                    <small id="error-penjualan_kode" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input type="datetime-local" name="penjualan_tanggal" class="form-control" 
                           value="{{ \Carbon\Carbon::parse($penjualan->penjualan_tanggal)->format('Y-m-d\TH:i') }}">
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
                            @foreach($penjualan->penjualanDetail as $detail)
                                <tr>
                                    <td>
                                        <select name="barang_id[]" class="form-control">
                                            <option value="">Pilih Barang</option>
                                            @foreach($barang as $item)
                                                <option value="{{ $item->barang_id }}" 
                                                    {{ $detail->barang_id == $item->barang_id ? 'selected' : '' }}>
                                                    {{ $item->barang_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="harga[]" class="form-control" value="{{ $detail->harga }}">
                                    </td>
                                    <td>
                                        <input type="number" name="jumlah[]" class="form-control" value="{{ $detail->jumlah }}">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger remove-barang">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-success" id="add-barang">Tambah Barang</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('select[name="user_id"]').change(function() {
            var pembeliName = $('option:selected', this).text().split(' - ')[0];
            $('#pembeli').val(pembeliName);
        });
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
        $("#form-edit").validate({
            rules: {
                user_id: { required: true },
                penjualan_kode: { required: true, minlength: 3 },
                penjualan_tanggal: { required: true, date: true },
                "barang_id[]": { required: true },
                "harga[]": { required: true, number: true, min: 1 },
                "jumlah[]": { required: true, digits: true, min: 1 }
            },
            submitHandler: function(form) {
                var formData = $(form).serialize();
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
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
                    }
                });
                return false;
            }
        });
    });
</script>
