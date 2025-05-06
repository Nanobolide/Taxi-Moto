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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passager_id')->constrained('users'); // L'ID du passager
            $table->foreignId('conducteur_id')->nullable()->constrained('users'); // L'ID du conducteur
            $table->string('depart'); // Adresse du départ
            $table->string('destination'); // Adresse de destination
            $table->enum('statut', ['en attente', 'acceptée', 'en cours', 'terminée'])->default('en attente'); // Statut de la course
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
