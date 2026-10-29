<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketMembership extends Model
{
    protected $fillable = [
        'nama_paket',
        'durasi_hari',
        'harga',
        'deskripsi',
        'status'
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function transaksiMemberships()
    {
        return $this->hasMany(TransaksiMembership::class);
    }
}
