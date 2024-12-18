<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Absensi Karyawan</title>
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
    <h1>Rekapitulasi Absensi Karyawan</h1>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Total Hadir</th>
                <th>Total Sakit</th>
                <th>Total Izin</th>
                <th>Total Alpha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekaps as $rekap)
                <tr>
                    {{-- Pastikan relasi karyawan diakses dengan validasi --}}
                    <td>{{ $rekap->karyawan->nama ?? 'Tidak Diketahui' }}</td>
                    {{-- Tambahkan helper untuk mengonversi format waktu jika perlu --}}
                    <td>{{ $rekap->total_hadir }}</td>
                    <td>{{ $rekap->total_sakit }}</td>
                    <td>{{ $rekap->total_izin }}</td>
                    <td>{{ $rekap->total_alpha }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
