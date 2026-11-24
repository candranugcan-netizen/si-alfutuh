<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('erapor_nilai') && !Schema::hasColumn('erapor_nilai', 'is_selected')) {
            Schema::table('erapor_nilai', function (Blueprint $table) {
                $table->boolean('is_selected')->default(true)->after('nilai');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('erapor_nilai') && Schema::hasColumn('erapor_nilai', 'is_selected')) {
            Schema::table('erapor_nilai', function (Blueprint $table) {
                $table->dropColumn('is_selected');
            });
        }
    }
};
