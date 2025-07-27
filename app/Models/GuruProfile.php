<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruProfile extends Model
{
    protected $fillable = ['user_id', 'nip', 'mapel', 'golongan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
