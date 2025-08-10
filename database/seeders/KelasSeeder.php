<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $tingkat = ['VII', 'VIII', 'IX'];
        $subkelas = ['A', 'B', 'C'];

        foreach ($tingkat as $level) {
            foreach ($subkelas as $sub) {
                Kelas::factory()->create([
                    'nama_kelas' => "{$level} {$sub}",
                    'tingkat_kelas' => $level,
                ]);
            }
        }
    }
}
