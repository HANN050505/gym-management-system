<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_memberships', function (Blueprint $table) {
    $table->id();
    $table->foreignId('member_id')
        ->constrained()
        ->cascadeOnDelete();
    $table->foreignId('paket_membership_id')
        ->constrained()
        ->cascadeOnDelete();
    $table->decimal('total',12,2);
    $table->date('tanggal_bayar');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_memberships');
    }
};
