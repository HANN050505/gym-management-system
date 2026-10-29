<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'member_id',
        'paket_membership_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function paket()
    {
        return $this->belongsTo(PaketMembership::class, 'paket_membership_id');
    }

    public function transaksiMemberships()
    {
        return $this->hasMany(TransaksiMembership::class);
    }
}
