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
        Schema::create('vetos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Lien avec l'utilisateur
            $table->foreignId('seller_id')->constrained()->onDelete('cascade'); // Lien avec le vendeur
            $table->foreignId('shelttergroomer_id')->constrained('sheltter_groomers')->onDelete('cascade');
           /*  $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('shelttergroomer_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('shelttergroomer_id')->references('id')->on('shelttergroomer')->onDelete('cascade'); */
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vetos');
    }
};
