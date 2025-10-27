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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->dateTime('hora_inicio');
            $table->dateTime('hora_fin');
            $table->foreignId('administrativo_id')->constrained('administrativos');
            $table->foreignId('dia_id')->constrained('dias');
            $table->foreignId('aula_id')->constrained('aulas');
            $table->foreignId('asignatura_id')->constrained('asignaturas');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
