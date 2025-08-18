<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'guru_id',
        'kode_mapel',
        'nama_mapel',
    ];

    public function guru()
    {
        return $this->belongsTo(GuruProfile::class);
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function adaptiveRules()
    {
        return $this->hasMany(AdaptiveRules::class);
    }
}
