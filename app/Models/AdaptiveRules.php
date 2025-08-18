<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdaptiveRules extends Model
{
    protected $table = 'adaptive_rules';

    protected $fillable = [
        'matpel_id',
        'materi_id',
        'dibuat_oleh',
        'operator',
        'nilai_batas',
    ];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'matpel_id');
    }

    public function materi()
    {
        return $this->belongsTo(MateriPelajaran::class, 'materi_id');
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
