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

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function paket()
    {
        return $this->belongsTo(PaketMembership::class,'paket_membership_id');
    }
}