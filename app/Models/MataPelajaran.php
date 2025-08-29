<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
    ];

    public function guru()
    {
        return $this->belongsToMany(GuruProfile::class, 'guru_mata_pelajaran', 'matpel_id', 'guru_id');
    }

    public function adaptiveRules()
    {
        return $this->hasMany(AdaptiveRules::class);
    }
}
