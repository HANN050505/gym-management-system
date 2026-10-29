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
        'foto',
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

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function activeMembership()
    {
        return $this->hasOne(Membership::class)
            ->where('status', 'aktif')
            ->latest('tanggal_berakhir');
    }
}
