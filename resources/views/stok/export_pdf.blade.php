<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid black;
        }
        th {
            background-color: #f2f2f2;
        }
        h3 {
            text-align: center;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/polinema-bw.png'))) }}"style="width: 60; height= 60;">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
            </td>
        </tr>
    </table>
    <h3>Laporan Data Stok Barang</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Supplier</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stok as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->supplier->supplier_nama }}</td>
                    <td>{{ $item->barang->barang_nama }}</td>
                    <td>{{ $item->stok_jumlah }}</td>
                    <td>{{ $item->stok_tanggal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
