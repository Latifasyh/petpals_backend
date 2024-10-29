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
            $table->foreignId('seller_id')->nullable()->constrained()->onDelete('cascade'); // Lien avec le vendeur
            $table->foreignId('sheltter_groomers_id')->nullable()->constrained()->onDelete('cascade'); // Lien avec le refuge
            $table->string('path'); // Chemin de la photo
           // $table->foreignId('veterinarian_id')->nullable()->constrained()->onDelete('cascade'); // Lien avec le vétérinaire
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
