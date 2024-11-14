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
        Schema::create('profession_Types', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['seller', 'veto', 'sheltter']); // Type de profession
            $table->string('business_name'); // Nom de l'entreprise
            $table->string('adresse'); // Adresse de l'entreprise
            $table->string('ville'); // Ville de l'entreprise
            $table->string('num_pro'); // Numéro professionnel
            $table->unsignedBigInteger('user_id'); // Clé étrangère vers l'utilisateur
            $table->timestamps();

             // Définir la relation entre profession_type et user
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profession_types');
    }
};
