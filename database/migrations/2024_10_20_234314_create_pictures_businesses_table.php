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
        Schema::create('pictures_businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id'); // ID de l'entité
            $table->string('entity_type'); // Type d'entité (Seller, SheltterGroomer, ou Veto)
            $table->string('path'); // Chemin de la photo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pictures_businesses');
    }
};
