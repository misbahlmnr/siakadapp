<?php

namespace Database\Seeders;

use App\Models\SemesterAjaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semesters = [
            ['semester' => 'Ganjil', 'tahun_ajaran' => '2023/2024', 'status_aktif' => false],
            ['semester' => 'Genap',  'tahun_ajaran' => '2023/2024', 'status_aktif' => false], // contoh aktif sekarang
            ['semester' => 'Ganjil', 'tahun_ajaran' => '2024/2025', 'status_aktif' => false],
            ['semester' => 'Genap',  'tahun_ajaran' => '2024/2025', 'status_aktif' => false],
            ['semester' => 'Ganjil', 'tahun_ajaran' => '2025/2026', 'status_aktif' => true],
            ['semester' => 'Genap',  'tahun_ajaran' => '2025/2026', 'status_aktif' => false],
        ];

        foreach ($semesters as $data) {
            SemesterAjaran::create($data);
        }

        $this->command->info(count($semesters) . " data semester berhasil dibuat.");
    }
}
