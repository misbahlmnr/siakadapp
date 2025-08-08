<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = [
        'nama_kelas',
        'tingkat_kelas'
    ];

    public function siswa()
    {
        return $this->hasMany(SiswaProfile::class);
    }
}
