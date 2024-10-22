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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th>ID Penjualan :</th>
                        <td>{{ $penjualan->penjualan_id }}</td>
                    </tr>
                    <tr>
                        <th>Kode :</th>
                        <td>{{ $penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th>Pembeli :</th>
                        <td>{{ $penjualan->pembeli }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal :</th>
                        <td>{{ $penjualan->penjualan_tanggal }}</td>
                    </tr>
                    <tr>
                        <th>User :</th>
                        <td>{{ $penjualan->user->nama }}</td>
                    </tr>
                    <tr>
                        <th>Level :</th>
                        <td>{{ $penjualan->user->level->level_nama }}</td>
                    </tr>
                </table>
                
                <h5>Detail Barang Penjualan</h5>
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penjualan->penjualanDetail as $detail)
                            <tr>
                                <td>{{ $detail->barang->barang_nama }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ number_format($detail->harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@endempty
