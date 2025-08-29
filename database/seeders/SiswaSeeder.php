<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SiswaProfile;
use App\Models\Kelas;
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $totalSiswa = 30;

        // Ambil semua kelas
        $kelasList = Kelas::all();
        $kelasCount = $kelasList->count();

        if ($kelasCount == 0) {
            $this->command->error('Tabel kelas kosong, tambahkan data kelas terlebih dahulu.');
            return;
        }

        for ($i = 1; $i <= $totalSiswa; $i++) {
            // Buat User dulu
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password123'),
                'role' => 'siswa',
            ]);

            // Tentukan kelas: looping agar setiap kelas dapat siswa secara merata
            $kelas = $kelasList[($i - 1) % $kelasCount];

            // Buat SiswaProfile
            SiswaProfile::create([
                'user_id' => $user->id,
                'kelas_id' => $kelas->id,
                'nis' => '2024' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nisn' => $faker->unique()->numerify('##########'),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date('Y-m-d', '2010-01-01'),
                'no_hp' => $faker->numerify('0812########'),
                'angkatan' => 2024,
                'status' => 'aktif',
                'nama_ortu' => $faker->name,
                'kontak_ortu' => $faker->numerify('0812########'),
            ]);
        }

        $this->command->info("Berhasil membuat {$totalSiswa} siswa dengan distribusi merata per kelas.");
    }
}
