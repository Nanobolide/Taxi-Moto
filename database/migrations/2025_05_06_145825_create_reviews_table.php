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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passager_id')->constrained('users'); // Le passager qui note
            $table->foreignId('conducteur_id')->constrained('users'); // Le conducteur noté
            $table->integer('note'); // La note donnée
            $table->text('commentaire')->nullable(); // Le commentaire facultatif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
