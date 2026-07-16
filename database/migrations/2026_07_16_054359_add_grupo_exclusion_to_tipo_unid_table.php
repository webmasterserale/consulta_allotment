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
        Schema::table('tipo_unid', function (Blueprint $table) {
            $table->string('grupo_exclusion', 20)->nullable()->after('ninos');
            $table->index(['hotel', 'grupo_exclusion'], 'idx_tipo_unid_hotel_grupo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipo_unid', function (Blueprint $table) {
            $table->dropIndex('idx_tipo_unid_hotel_grupo');
            $table->dropColumn('grupo_exclusion');
        });
    }
};
