<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'students';
    
    protected $fillable = [
        'nis',
        'nama',
        'jenis_kelamin',
        'kelas',
        'tahun_masuk',
        'ttl',
        'alamat',
        'kontak_ortu',
        'status',
    ];
}
