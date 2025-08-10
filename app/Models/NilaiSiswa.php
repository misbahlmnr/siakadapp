<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiSiswa extends Model
{
    protected $table = 'nilai_siswa';

    protected $fillable = [
        'tugas_ujian_id',
        'siswa_id',
        'nilai',
        'catatan',
    ];

    public function tugasUjian()
    {
        return $this->belongsTo(TugasUjian::class);
    }

    public function guru()
    {
        return $this->belongsTo(GuruProfile::class);
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
