<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nisn',
        'kelas',
        'tahun_masuk',
        'alamat',
        'kontak_ortu',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
