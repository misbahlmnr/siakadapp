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
                'guru_id' => null,
                'kode_mapel' => 'MTK01',
                'nama_mapel' => 'Matematika',
            ],
            [
                'id' => 2,
                'guru_id' => null,
                'kode_mapel' => 'BIND01',
                'nama_mapel' => 'Bahasa Indonesia',
            ],
            [
                'id' => 3,
                'guru_id' => null,
                'kode_mapel' => 'BING01',
                'nama_mapel' => 'Bahasa Inggris',
            ],
            [
                'id' => 4,
                'guru_id' => null,
                'kode_mapel' => 'IPA01',
                'nama_mapel' => 'Ilmu Pengetahuan Alam (IPA)',
            ],
            [
                'id' => 5,
                'guru_id' => null,
                'kode_mapel' => 'IPS01',
                'nama_mapel' => 'Ilmu Pengetahuan Sosial (IPS)',
            ],
            [
                'id' => 6,
                'guru_id' => null,
                'kode_mapel' => 'PKN01',
                'nama_mapel' => 'Pendidikan Kewarganegaraan (PKN)',
            ],
            [
                'id' => 7,
                'guru_id' => null,
                'kode_mapel' => 'SENI01',
                'nama_mapel' => 'Seni Budaya',
            ],
            [
                'id' => 8,
                'guru_id' => null,
                'kode_mapel' => 'PJOK01',
                'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan (PJOK)',
            ],
            [
                'id' => 9,
                'guru_id' => null,
                'kode_mapel' => 'TIK01',
                'nama_mapel' => 'Teknologi Informasi dan Komunikasi (TIK)',
            ],
            [
                'id' => 10,
                'guru_id' => null,
                'kode_mapel' => 'AGAMA01',
                'nama_mapel' => 'Pendidikan Agama',
            ],
        ];

        foreach ($mapels as $mapel) {
            MataPelajaran::factory()->create($mapel);
        }
    }
}
