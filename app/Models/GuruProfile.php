<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'email',
        'no_telp',
        'alamat',
        'status_guru',
        'tanggal_masuk',
        'matpel_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'matpel_id');
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }
}
