<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $fillable = [
        'member_id',
        'membership_id',
        'judul',
        'pesan',
        'tipe',
        'dibaca'
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
}
