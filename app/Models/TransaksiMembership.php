<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiMembership extends Model
{
    protected $fillable = [
        'member_id',
        'paket_membership_id',
        'total',
        'tanggal_bayar'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function paket()
    {
        return $this->belongsTo(
            PaketMembership::class,
            'paket_membership_id'
        );
    }
}