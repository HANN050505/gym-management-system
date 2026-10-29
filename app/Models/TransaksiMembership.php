<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiMembership extends Model
{
    protected $fillable = [
        'member_id',
        'paket_membership_id',
        'membership_id',
        'total',
        'metode_pembayaran',
        'status_pembayaran',
        'catatan',
        'tanggal_bayar'
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'total' => 'decimal:2',
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

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
}
