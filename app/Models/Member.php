<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'kode_member',
        'nama',
        'email',
        'no_hp',
        'alamat',
        'qr_code',
        'status'
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function transaksiMemberships()
    {
        return $this->hasMany(TransaksiMembership::class);
    }

    
}