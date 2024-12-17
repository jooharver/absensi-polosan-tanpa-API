<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Absensi Karyawan</title>
    <style>
        /* Global styling */
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 20px;
        }

        .kop {
            display: flex;
            align-items: center;
            justify-content: flex-start;  /* Logo dan teks akan di kiri */
            margin-bottom: 20px;
            width: 100%; /* Pastikan lebar penuh pada halaman */
        }

        .img {
            width: 80px; /* Ukuran logo */
            height: auto; /* Menjaga proporsi */
            margin-right: 15px; /* Memberikan jarak antara logo dan teks */
            flex: auto;
            flex-direction: row;
        }

        .tulisan {
            display: flex;
            flex-direction: column; /* Menyusun teks secara vertikal */
            text-align: left;  /* Agar teks tetap rata kiri */
        }


        .line {
            height: 4px;
            background-color: #1e5432;
            margin-top: 10px;
        }

        .underline {
            height: 2px;
            background-color: #1e5432;
            margin-top: 3px;
            margin-bottom: 20px;
        }


        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #f9f9f9;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #1e5432;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #edf4e7;
        }

        .date-location {
            text-align: right;
            margin-bottom: 10px;
            margin-right: 14px;
            font-size: 16px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="kop">
        <div>
            <img class="img" src="{{ url('images/logo.jpeg') }}" alt="logo">
        </div>
        <div class="tulisan">
            <h2>PR UD Putra Bintang Timur</h2>
            <p>Jl. Imam Bonjol No. 885, RT. 004 RW. 008, Ardimulyo, Kec. Singosari, Kab. Malang - Jawa Timur 65153</p>
            <p>Telepon: 0341-453456 | Email: prudputrabintangtimur@gmail.com</p>
        </div>
    </div>
    <div class="line"></div>
    <div class="underline"></div>
    <p class="date-location">
        Malang, {{ date('d F Y') }}
    </p>

    <!-- Title -->
    <h2 style="text-align: center;">Rekapitulasi Absensi Karyawan Tahun {{ date('Y')}}</h2>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Bulan</th>
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
                    <td>{{ $rekap->bulan }}</td>
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