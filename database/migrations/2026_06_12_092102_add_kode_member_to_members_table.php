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
        if (!Schema::hasColumn('members', 'kode_member')) {
            Schema::table('members', function (Blueprint $table) {
                $table->string('kode_member')
                      ->unique()
                      ->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('members', 'kode_member')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropColumn('kode_member');
            });
        }
    }
};
