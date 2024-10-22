<form action="{{ url('/stok/' . $stok->stok_id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Stok Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Supplier</label>
                    <select name="supplier_id" class="form-control">
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->supplier_id }}" 
                                {{ $stok->supplier_id == $supplier->supplier_id ? 'selected' : '' }}>
                                {{ $supplier->supplier_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-supplier_id" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Barang</label>
                    <select name="barang_id" class="form-control">
                        <option value="">Pilih Barang</option>
                        @foreach($barang as $item)
                            <option value="{{ $item->barang_id }}" 
                                {{ $stok->barang_id == $item->barang_id ? 'selected' : '' }}>
                                {{ $item->barang_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-barang_id" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="datetime-local" name="stok_tanggal" class="form-control" 
                        value="{{ \Carbon\Carbon::parse($stok->stok_tanggal)->format('Y-m-d\TH:i') }}">
                    <small id="error-stok_tanggal" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jumlah Stok</label>
                    <input type="number" name="stok_jumlah" class="form-control" 
                        value="{{ $stok->stok_jumlah }}">
                    <small id="error-stok_jumlah" class="error-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-edit").validate({
            rules: {
                supplier_id: { required: true },
                barang_id: { required: true },
                stok_tanggal: { required: true },
                stok_jumlah: { required: true, digits: true }
            },
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
                            dataStok.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.errors, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
