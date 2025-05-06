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
            $table->foreignId('passager_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conducteur_id')->constrained('users')->onDelete('cascade');
            $table->string('depart');
            $table->string('destination');
            $table->string('statut')->default('en attente'); // Statut de la course
            $table->text('incidents')->nullable(); // Champ pour enregistrer des incidents
            $table->decimal('latitude_depart', 10, 8)->nullable(); // Latitude du départ
            $table->decimal('longitude_depart', 11, 8)->nullable(); // Longitude du départ
            $table->decimal('latitude_arrivee', 10, 8)->nullable(); // Latitude d'arrivée
            $table->decimal('longitude_arrivee', 11, 8)->nullable(); // Longitude d'arrivée
            $table->timestamp('depart_time')->nullable(); // Heure du départ
            $table->timestamp('arrivee_time')->nullable(); // Heure de l'arrivée
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
