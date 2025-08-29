<?php

namespace Database\Seeders;

use App\Models\GuruMataPelajaran;
use App\Models\GuruProfile;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh 3 guru
        $guruData = [
            [
                'name' => 'Herman Santoso',
                'email' => 'herman@gmail.com',
                'password' => bcrypt('admin123'),
                'nip' => '1988510101',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1980-01-01',
                'alamat' => 'Jl. Contoh No.1',
                'no_hp' => '081234567890',
                'status_kepegawaian' => 'pns',
            ],
            [
                'name' => 'Dian Santoso',
                'email' => 'dian@gmail.com',
                'password' => bcrypt('admin123'),
                'nip' => '1980015101',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1980-01-01',
                'alamat' => 'Jl. Contoh No.1',
                'no_hp' => '081234567890',
                'status_kepegawaian' => 'pns',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => bcrypt('admin123'),
                'nip' => '1980010101',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1980-01-01',
                'alamat' => 'Jl. Contoh No.1',
                'no_hp' => '081234567890',
                'status_kepegawaian' => 'pns',
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@gmail.com',
                'password' => bcrypt('admin123'),
                'nip' => '1985020202',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1985-02-02',
                'alamat' => 'Jl. Contoh No.2',
                'no_hp' => '081234567891',
                'status_kepegawaian' => 'honorer',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@gmail.com',
                'password' => bcrypt('admin123'),
                'nip' => '1990010303',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1990-03-03',
                'alamat' => 'Jl. Contoh No.3',
                'no_hp' => '081234567892',
                'status_kepegawaian' => 'pns',
            ],
        ];

        foreach ($guruData as $data) {
            // 1️⃣ Buat User
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'guru',
            ]);

            // 2️⃣ Buat GuruProfile
            $guruProfile = GuruProfile::create([
                'user_id' => $user->id,
                'nip' => $data['nip'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'alamat' => $data['alamat'],
                'no_hp' => $data['no_hp'],
                'status_kepegawaian' => $data['status_kepegawaian'],
            ]);

            $mapelIds = MataPelajaran::pluck('id')->random(10);
            foreach ($mapelIds as $mapelId) {
                GuruMataPelajaran::create([
                    'guru_id' => $guruProfile->id,
                    'matpel_id' => $mapelId,
                ]);
            }
        }
    }
}
