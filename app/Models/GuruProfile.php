<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'mapel',
        'email',
        'no_telp',
        'alamat',
        'status_guru',
        'tanggal_masuk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
