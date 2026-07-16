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
        Schema::connection('mysql_allotment')->create('combinaciones', function (Blueprint $table) {
            $table->id();
            $table->integer('hotel');
            $table->unsignedTinyInteger('adultos');
            $table->unsignedTinyInteger('ninos');
            $table->unsignedTinyInteger('total');
            $table->unsignedSmallInteger('prioridad')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['hotel', 'adultos', 'ninos', 'activo'], 'idx_comb_hotel_pax');
        });

        Schema::connection('mysql_allotment')->create('detalle_combinaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combinacion_id')->constrained('combinaciones')->cascadeOnDelete();
            $table->integer('tipo_unid_id');
            $table->unsignedTinyInteger('cantidad')->default(1);

            $table->foreign('tipo_unid_id')->references('id')->on('tipo_unid');
            $table->unique(['combinacion_id', 'tipo_unid_id'], 'uq_detalle_comb_tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_allotment')->dropIfExists('detalle_combinaciones');
        Schema::connection('mysql_allotment')->dropIfExists('combinaciones');
    }
};
