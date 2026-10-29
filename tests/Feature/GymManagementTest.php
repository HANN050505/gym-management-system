<?php

use App\Models\User;
use App\Models\Member;
use App\Models\PaketMembership;
use App\Models\Membership;
use App\Models\Absensi;
use Carbon\Carbon;

test('unauthenticated users are redirected to login from gym routes', function () {
    $response = $this->get('/dashboard-gym');
    $response->assertRedirect('/login');

    $response = $this->get('/members');
    $response->assertRedirect('/login');

    $response = $this->get('/paket-memberships');
    $response->assertRedirect('/login');

    $response = $this->get('/transaksi-membership');
    $response->assertRedirect('/login');
});

test('authenticated user can view gym dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard-gym');
    $response->assertStatus(200);
    $response->assertSee('Dashboard Gym');
});

test('authenticated user can create member with auto-generated code', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/members', [
        'nama' => 'Test Athlete',
        'no_hp' => '089999999999',
        'email' => 'athlete@test.com',
        'alamat' => 'Jl. Test No. 1',
    ]);

    $response->assertRedirect(route('members.index'));
    $this->assertDatabaseHas('members', [
        'nama' => 'Test Athlete',
        'no_hp' => '089999999999',
    ]);
});

test('purchasing membership activates member and records transaction', function () {
    $user = User::factory()->create();
    $member = Member::create([
        'kode_member' => 'TEST-001',
        'nama' => 'Member Test',
        'no_hp' => '0812345678',
        'status' => 'expired',
    ]);
    $paket = PaketMembership::create([
        'nama_paket' => 'Paket Uji Coba',
        'durasi_hari' => 30,
        'harga' => 150000,
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($user)->post('/transaksi-membership', [
        'member_id' => $member->id,
        'paket_id' => $paket->id,
        'metode_pembayaran' => 'cash',
        'catatan' => 'Uji coba pembayaran',
    ]);

    $response->assertRedirect(route('transaksi-membership.index'));

    $member->refresh();
    expect($member->status)->toBe('aktif');

    $this->assertDatabaseHas('transaksi_memberships', [
        'member_id' => $member->id,
        'paket_membership_id' => $paket->id,
        'total' => 150000,
        'metode_pembayaran' => 'cash',
    ]);

    $this->assertDatabaseHas('memberships', [
        'member_id' => $member->id,
        'paket_membership_id' => $paket->id,
        'status' => 'aktif',
    ]);
});

test('member can record attendance with active membership and prevents same-day duplicate', function () {
    $user = User::factory()->create();
    $member = Member::create([
        'kode_member' => 'TEST-002',
        'nama' => 'Active Member Test',
        'no_hp' => '0812345679',
        'status' => 'aktif',
    ]);

    // Check-in pertama
    $response = $this->actingAs($user)->post('/absensi', [
        'kode_member' => 'TEST-002',
    ]);
    $response->assertSessionHas('success');

    expect(Absensi::where('member_id', $member->id)->exists())->toBeTrue();

    // Check-in kedua di hari yang sama -> muncul warning
    $response2 = $this->actingAs($user)->post('/absensi', [
        'kode_member' => 'TEST-002',
    ]);
    $response2->assertSessionHas('warning');
});

test('authenticated user can view dedicated DOEL GYM member card with barcode and identity', function () {
    $user = User::factory()->create();
    $member = Member::create([
        'kode_member' => 'MBR-2026-9999',
        'nama' => 'Doel Perkasa',
        'no_hp' => '081299998888',
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($user)->get("/members/{$member->id}/kartu");
    $response->assertStatus(200);
    $response->assertSee('DOEL GYM');
    $response->assertSee('MBR-2026-9999');
    $response->assertSee('Doel Perkasa');
    $response->assertSee('<svg', false); // SVG barcode/QR
});

test('authenticated user can view and print official transaction receipt (struk)', function () {
    $user = User::factory()->create();
    $member = Member::create([
        'kode_member' => 'MBR-2026-8888',
        'nama' => 'Pelanggan Setia',
        'no_hp' => '081277776666',
        'status' => 'aktif',
    ]);
    $paket = PaketMembership::create([
        'nama_paket' => 'Paket Bulanan',
        'durasi_hari' => 30,
        'harga' => 250000,
        'status' => 'aktif',
    ]);
    $transaksi = \App\Models\TransaksiMembership::create([
        'member_id' => $member->id,
        'paket_membership_id' => $paket->id,
        'total' => 250000,
        'metode_pembayaran' => 'cash',
        'status_pembayaran' => 'lunas',
        'tanggal_bayar' => Carbon::today(),
    ]);

    $response = $this->actingAs($user)->get("/transaksi-membership/{$transaksi->id}/struk");
    $response->assertStatus(200);
    $response->assertSee('DOEL GYM');
    $response->assertSee('STRUK PEMBAYARAN RESMI');
    $response->assertSee('Pelanggan Setia');
    $response->assertSee('250.000');
});
