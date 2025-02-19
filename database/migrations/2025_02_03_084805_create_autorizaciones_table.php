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
        Schema::create('autorizaciones', function (Blueprint $table) {
            $table->id('id_autorisacion');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('motivo');
            $table->timestamp('fecha');  // No hay problema con esta columna
            $table->timestamp('hora_inicio')->nullable();  // Permitir que sea NULL si no se define un valor
            $table->timestamp('hora_expiracion')->nullable();  // También permitir que sea NULL
            $table->enum('estado', ['autorizado', 'no_autorizado'])->default('no_autorizado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autorizaciones');
    }
};
