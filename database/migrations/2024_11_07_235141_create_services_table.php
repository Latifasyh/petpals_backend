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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du service (ex : Toiletteur, Refuse Trainer, etc.)
            $table->string('picture'); // Lien vers l'image
            $table->string('category'); // Catégorie du service
            $table->text('description'); // Description du service
            $table->decimal('price', 8, 2); // Prix
            $table->enum('price_type', ['par séance', 'par jour']); // Type de prix (par séance ou par jour)
            $table->foreignId('profession_type_id')->constrained()->onDelete('cascade'); // Clé étrangère vers la table profession_types
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
