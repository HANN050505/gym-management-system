<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'member_id',
        'tanggal',
        'jam'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}