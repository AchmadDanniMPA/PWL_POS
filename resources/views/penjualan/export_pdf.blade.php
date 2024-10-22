<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Penjualan</title>
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
    <h3>Laporan Data Penjualan</h3>
    <table>
        <thead>
            <tr>
                <th>Kode Penjualan</th>
                <th>Nama Pembeli</th>
                <th>Tanggal Penjualan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $sale)
                <tr>
                    <td>{{ $sale->penjualan_kode }}</td>
                    <td>{{ $sale->pembeli }}</td>
                    <td>{{ $sale->penjualan_tanggal }}</td>
                    <td>{{ number_format($sale->total_harga, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
