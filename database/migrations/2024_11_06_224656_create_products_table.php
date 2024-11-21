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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('category');
            $table->string('animal_type');
            $table->text('description');
            $table->string('picture')->nullable();
            $table->decimal('price', 8, 2);
            $table->foreignId('profession_type_id')->constrained('profession_types')->onDelete('cascade');
           // $table->foreignId('seller_id')->constrained('sellers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
