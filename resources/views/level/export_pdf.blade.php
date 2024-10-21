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
