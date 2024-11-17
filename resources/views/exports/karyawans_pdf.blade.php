<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
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
    <h1>Data Karyawan Perusahaan Trio Macan</h1>
    <table>
        <thead>
            <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Tanggal Lahir</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>Agama</th>
                <th>No Telepon</th>
                <th>Email</th>
                <th>Tanggal Masuk</th>
                <th>Posisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($karyawans as $karyawan)
                <tr>
                    <td>{{ $karyawan->nik }}</td>
                    <td>{{ $karyawan->nama }}</td>
                    <td>{{ $karyawan->tanggal_lahir ? \Carbon\Carbon::parse($karyawan->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $karyawan->jenis_kelamin }}</td>
                    <td>{{ $karyawan->alamat }}</td>
                    <td>{{ $karyawan->agama }}</td>
                    <td>{{ $karyawan->no_telepon }}</td>
                    <td>{{ $karyawan->email }}</td>
                    <td>{{ $karyawan->tanggal_masuk ? \Carbon\Carbon::parse($karyawan->tanggal_masuk)->format('d-m-Y H:i') : '-' }}</td>
                    <td>{{ $karyawan->posisi ? $karyawan->posisi->posisi : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
