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
        if (!Schema::hasColumn('members', 'status')) {
            Schema::table('members', function (Blueprint $table) {
                $table->enum('status', [
                    'aktif',
                    'expired'
                ])->default('expired');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('members', 'status')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
