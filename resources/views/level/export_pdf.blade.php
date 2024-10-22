<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Level</title>
    <style>
        body { font-family: 'Arial'; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 12px; border: 1px solid #000; }
        th { background-color: #f2f2f2; }
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
    <h3 class="text-center">LAPORAN DATA LEVEL</h3>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Level</th>
                <th>Nama Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($level as $l)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $l->level_kode }}</td>
                    <td>{{ $l->level_nama }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
