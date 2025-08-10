<?php

namespace Database\Factories;

use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class MataPelajaranFactory extends Factory
{
    protected $model = MataPelajaran::class;

    public function definition()
    {
        return [
            'kode_mapel' => strtoupper($this->faker->unique()->bothify('???##')), // Contoh: ABC12
            'nama_mapel' => $this->faker->word(),
            'deskripsi' => $this->faker->sentence(),
        ];
    }
}
