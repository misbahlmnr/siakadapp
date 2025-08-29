<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Nilai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN NILAI SISWA</h1>
        <p>Sistem Informasi Akademik</p>
        <p>Tanggal: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Guru</th>
                <th>Jenis</th>
                <th>Nilai</th>
                <th>Semester</th>
                <th>Tahun Ajaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->siswa->user->name ?? '-' }}</td>
                <td>{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                <td>{{ $item->evaluasiPembelajaran->guruMatpel->mataPelajaran->nama_mapel ?? '-' }}</td>
                <td>{{ $item->evaluasiPembelajaran->guruMatpel->guru->user->name ?? '-' }}</td>
                <td>{{ $item->evaluasiPembelajaran->jenis ?? '-' }}</td>
                <td>{{ $item->nilai ?? '-' }}</td>
                <td>{{ ucfirst($item->evaluasiPembelajaran->semesterAjaran->semester) ?? '-' }}</td>
                <td>{{ $item->evaluasiPembelajaran->semesterAjaran->tahun_ajaran ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
