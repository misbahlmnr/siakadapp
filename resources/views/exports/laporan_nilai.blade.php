<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Mapel</th>
            <th>Guru</th>
            <th>Jenis</th>
            <th>Nilai</th>
            <th>Semester</th>
            <th>Tahun Ajaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $row)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $row->siswa->user->name ?? '-' }}</td>
            <td>{{ $row->siswa->kelas->nama_kelas ?? '-' }}</td>
            <td>{{ $row->jadwal->matpel->nama_mapel ?? '-' }}</td>
            <td>{{ $row->guru->user->name ?? '-' }}</td>
            <td>{{ $row->jenis }}</td>
            <td>{{ $row->nilai }}</td>
            <td>{{ $row->semester }}</td>
            <td>{{ $row->tahun_ajarannya }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
