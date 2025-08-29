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
            ],
            [
                'id' => 2,
                'kode_mapel' => 'BIND01',
                'nama_mapel' => 'Bahasa Indonesia',
            ],
            [
                'id' => 3,
                'kode_mapel' => 'BING01',
                'nama_mapel' => 'Bahasa Inggris',
            ],
            [
                'id' => 4,
                'kode_mapel' => 'IPA01',
                'nama_mapel' => 'Ilmu Pengetahuan Alam (IPA)',
            ],
            [
                'id' => 5,
                'kode_mapel' => 'IPS01',
                'nama_mapel' => 'Ilmu Pengetahuan Sosial (IPS)',
            ],
            [
                'id' => 6,
                'kode_mapel' => 'PKN01',
                'nama_mapel' => 'Pendidikan Kewarganegaraan (PKN)',
            ],
            [
                'id' => 7,
                'kode_mapel' => 'SENI01',
                'nama_mapel' => 'Seni Budaya',
            ],
            [
                'id' => 8,
                'kode_mapel' => 'PJOK01',
                'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan (PJOK)',
            ],
            [
                'id' => 9,
                'kode_mapel' => 'TIK01',
                'nama_mapel' => 'Teknologi Informasi dan Komunikasi (TIK)',
            ],
            [
                'id' => 10,
                'kode_mapel' => 'AGAMA01',
                'nama_mapel' => 'Pendidikan Agama',
            ],
        ];

        foreach ($mapels as $mapel) {
            MataPelajaran::factory()->create($mapel);
        }
    }
}
