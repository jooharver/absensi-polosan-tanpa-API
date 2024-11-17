<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data THR</title>
    <style>
        /* Tambahkan styling sesuai kebutuhan */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
                /* Styling untuk judul */
                h1 {
            text-align: center; /* Menempatkan judul di tengah */
        }
    </style>
</head>
<body>
    <h1>Data THR Perusahaan Trio Macan</h1>
    <table>
        <thead>
            <tr>
                <th>ID THR</th>
                <th>Nama</th>
                <th>THR</th>
                <th>Created at</th>
                <th>Updated at</th>
            </tr>
        </thead>
        <tbody>
            @foreach($thrs as $thr)
                <tr>
                    <td>{{ $thr->id_thr }}</td>
                    <td>{{ $thr->karyawan->nama }}</td>
                    <td>{{ 'Rp. ' . number_format($thr->thr, 0, ',', '.') }}</td>
                    <td>{{ $thr->created_at ? \Carbon\Carbon::parse($thr->created_at)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $thr->created_at ? \Carbon\Carbon::parse($thr->created_at)->format('d-m-Y') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
