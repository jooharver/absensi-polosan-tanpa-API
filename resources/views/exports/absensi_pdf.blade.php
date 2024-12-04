<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi</title>
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
    <h1>Data Absensi Perusahaan Trio Macan</h1>
    <table>
        <thead>
            <tr>
                <th>ID Absensi</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Durasi</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensis as $absensi)
                <tr>
                    <td>{{ $absensi->id_absensi }}</td>
                    <td>{{ $absensi->karyawan->nama }}</td>
                    <td>{{ $absensi->tanggal ? \Carbon\Carbon::parse($absensi->tanggal)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $absensi->jam_masuk }}</td>
                    <td>{{ $absensi->jam_keluar }}</td>
                    {{-- <td>{{ $absensi->durasi . ' jam' }}</td> --}}
                    <td>{{ $absensi->status }}</td>
                    <td>{{ $absensi->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
