<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

class KelasFactory extends Factory
{
    protected $model = Kelas::class;

    public function definition()
    {
        $tingkat = $this->faker->randomElement(['VII', 'VIII', 'IX']);
        $subkelas = $this->faker->randomElement(['A', 'B', 'C']);

        return [
            'nama_kelas' => "{$tingkat} {$subkelas}",
            'tingkat' => $tingkat,
            'tahun_ajaran' => '2025/2026',
        ];
    }
}
