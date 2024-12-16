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
            flex-wrap: wrap;
            flex-direction: row;
            align-items:baseline;
            margin-bottom: 20px;
        }

        .img {
            width: 80px; /* Atur ulang ukuran logo */
            height: auto; /* Memastikan aspek rasio tetap */
            align-items: center;
        }

        /* .tulisan {
            text-align: center;
            display: flex;
        } */

        /* .kop h2 {
            margin: 0;
            color: black;
            font-size: 24px;
        } */

        /* .kop p {
            margin: 5px 0;
            font-size: 14px;
        } */

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
            <h2>PT. MENCARI CINTA SEJATI</h2>
            <p>Jl. Kebahagiaan No. 1, Jakarta - Indonesia</p>
            <p>Telepon: (021) 12345678 | Email: info@mencaricintasejati.co.id</p>
        </div>
    </div>
    <div class="line"></div>
    <div class="underline"></div>
    <p class="date-location">
        Malang, {{ date('d F Y') }}
    </p>

    <!-- Title -->
    <h2 style="text-align: center;">Rekapitulasi Absensi Karyawan {{ date('Y')}}</h2>

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