<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'status_kepegawaian',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mataPelajaran()
    {
        return $this->hasMany(MataPelajaran::class, 'matpel_id');
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function evaluasiPembelajaran()
    {
        return $this->hasMany(EvaluasiPembelajaran::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
}
