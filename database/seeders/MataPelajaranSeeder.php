<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    public function run()
    {
        $mapels = [
            [
                'id' => 1,
                'kode_mapel' => 'MTK01',
                'nama_mapel' => 'Matematika',
                'deskripsi' => 'Mata pelajaran matematika',
            ],
            [
                'id' => 2,
                'kode_mapel' => 'BIND01',
                'nama_mapel' => 'Bahasa Indonesia',
                'deskripsi' => 'Mata pelajaran Bahasa Indonesia',
            ],
            [
                'id' => 3,
                'kode_mapel' => 'BING01',
                'nama_mapel' => 'Bahasa Inggris',
                'deskripsi' => 'Mata pelajaran Bahasa Inggris',
            ],
            [
                'id' => 4,
                'kode_mapel' => 'IPA01',
                'nama_mapel' => 'Ilmu Pengetahuan Alam (IPA)',
                'deskripsi' => 'Mata pelajaran IPA',
            ],
            [
                'id' => 5,
                'kode_mapel' => 'IPS01',
                'nama_mapel' => 'Ilmu Pengetahuan Sosial (IPS)',
                'deskripsi' => 'Mata pelajaran IPS',
            ],
            [
                'id' => 6,
                'kode_mapel' => 'PKN01',
                'nama_mapel' => 'Pendidikan Kewarganegaraan (PKN)',
                'deskripsi' => 'Mata pelajaran PKN',
            ],
            [
                'id' => 7,
                'kode_mapel' => 'SENI01',
                'nama_mapel' => 'Seni Budaya',
                'deskripsi' => 'Mata pelajaran Seni Budaya',
            ],
            [
                'id' => 8,
                'kode_mapel' => 'PJOK01',
                'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan (PJOK)',
                'deskripsi' => 'Mata pelajaran PJOK',
            ],
            [
                'id' => 9,
                'kode_mapel' => 'TIK01',
                'nama_mapel' => 'Teknologi Informasi dan Komunikasi (TIK)',
                'deskripsi' => 'Mata pelajaran TIK',
            ],
            [
                'id' => 10,
                'kode_mapel' => 'AGAMA01',
                'nama_mapel' => 'Pendidikan Agama',
                'deskripsi' => 'Mata pelajaran Pendidikan Agama',
            ],
        ];

        foreach ($mapels as $mapel) {
            MataPelajaran::factory()->create($mapel);
        }
    }
}
